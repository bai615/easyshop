<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\data\Pagination;
use common\utils\CommonTools;
use common\models\Payment;
use common\models\Member;
use common\models\Order;
use common\models\Areas;
use common\models\Favorite;
use common\models\Address;
use common\models\User;
use frontend\models\AccountLog;
use frontend\logics\OrderLogic;

/**
 * 用户中心模块
 *
 * @author baihua <baihua_2011@163.com>
 */
class UcenterController extends BaseController {

    public $currentMenu = 0;
    public $menuData = array(
        '1' => array(
            'name' => '我的订单',
            'url' => '/ucenter/order'
        ),
        '2' => array(
            'name' => '账户余额',
            'url' => '/ucenter/account'
        ),
        '3' => array(
            'name' => '我的收藏',
            'url' => '/ucenter/favorite'
        ),
        '4' => array(
            'name' => '地址管理',
            'url' => '/ucenter/address'
        ),
        '5' => array(
            'name' => '个人资料',
            'url' => '/ucenter/info'
        ),
        '6' => array(
            'name' => '修改密码',
            'url' => '/ucenter/password'
        ),
    );

    /**
     * 余额付款
     */
    public function actionPaymentBalance() {
        $userId = $this->data['shopUserInfo']['userId'];

        $return['attach'] = intval(Yii::$app->request->post('attach'));
        $return['total_fee'] = sprintf('%.2f', floatval(Yii::$app->request->post('total_fee')));
        $return['order_no'] = Yii::$app->request->post('order_no');
        $return['return_url'] = Yii::$app->request->post('return_url');
        $oldSign = Yii::$app->request->post('sign');

        if ($return['total_fee'] < 0 || $return['order_no'] == '' || $return['return_url'] == '') {
            CommonTools::showWarning('支付参数不正确');
        }

        $paymentObj = new Payment();
        $paymentInfo = $paymentObj->find()
            ->select(['id'])
            ->where('class_name = "BalancePay" ')
            ->one();

        $partnerKey = Payment::getConfigParam($paymentInfo['id'], 'M_PartnerKey');
        $newSign = CommonTools::getSign($return, $userId . $partnerKey);
        if ($oldSign != $newSign) {
            CommonTools::showWarning('数据校验不正确');
        }
        //获取用户信息
        $memberObj = new Member();
        $memberInfo = $memberObj->find()
            ->where('user_id=:userId', [':userId' => $userId])
            ->one();
        if (empty($memberInfo)) {
            CommonTools::showWarning('用户信息不存在');
        }
        //获取订单信息
        $orderObj = new Order();
        $orderInfo = $orderObj->find()
            ->where('order_no=:orderNo and pay_status = 0 and status = 1 and user_id=:userId', [':orderNo' => $return['order_no'], ':userId' => $userId])
            ->one();
        if (empty($orderInfo)) {
            CommonTools::showWarning('订单号【' . $return['order_no'] . '】已经被处理过，请查看订单状态');
        }
        if ($memberInfo['balance'] < $orderInfo['order_amount']) {
            CommonTools::showWarning('账户余额不足，请到用户中心充值');
        }

        //扣除余额并且记录日志
        $logObj = new AccountLog();
        $config = array(
            'user_id' => $userId,
            'event' => 'pay',
            'amount' => $return['total_fee'],
            'order_no' => $return['order_no'],
        );
        $is_success = $logObj->write($config);
        if (true !== $is_success) {
            CommonTools::showWarning('用户余额更新失败');
        }

        $orderId = OrderLogic::updateOrderStatus($return['order_no']);
        if (empty($orderId)) {
            CommonTools::showWarning('订单号【' . $return['order_no'] . '】更新失败');
        }
        $return['is_success'] = $is_success ? 'T' : 'F';
        ksort($return);

        //返还的URL地址
        $responseUrl = '';
        foreach ($return as $key => $val) {
            $responseUrl .= $key . '=' . urlencode($val) . '&';
        }
        $nextUrl = urldecode($return['return_url']);
        if (stripos($nextUrl, '?') === false) {
            $return_url = $nextUrl . '?' . $responseUrl;
        } else {
            $return_url = $nextUrl . '&' . $responseUrl;
        }
        $responseUrl = substr($responseUrl, 0, -1);
        //计算要发送的md5校验
        $urlStrMD5 = md5($responseUrl . $userId . $partnerKey);

        //拼接进返还的URL中
        $return_url.= 'sign=' . $urlStrMD5;
        header('location:' . $return_url);
    }

    /**
     * 我的订单
     */
    public function actionOrder() {
        $this->is_login();
        $this->currentMenu = 1;
        $userId = $this->data['shopUserInfo']['userId'];
        $data = Order::find()->where('user_id =:userId and is_del= 0', [':userId' => $userId]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $model = $data->where('user_id =:userId and is_del= 0', [':userId' => $userId])
            ->orderBy('id desc')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return $this->render('order', [
                'orderList' => $model,
                'pages' => $pages,
        ]);
    }

    /**
     * 订单详情
     */
    public function actionOrderDetail() {
        $this->is_login();
        $this->currentMenu = 1;
        $userId = $this->data['shopUserInfo']['userId'];
        $orderId = Yii::$app->request->get('id');
        //订单信息
        $orderModel = new Order();
        $orderInfo = $orderModel->find()
            ->where('id=:orderId and user_id=:userId', [':orderId' => $orderId, ':userId' => $userId])
            ->one();
        //地址信息
        $areaData = Areas::name($orderInfo['province'], $orderInfo['city'], $orderInfo['area']);
        return $this->render('orderDetail', array('orderInfo' => $orderInfo, 'areaData' => $areaData));
    }

    /**
     * 账户余额
     */
    public function actionAccount() {
        $this->is_login();
        $this->currentMenu = 2;
        $userId = $this->data['shopUserInfo']['userId'];
        $memberModel = new Member();
        $memberInfo = $memberModel->find()
            ->select(['user_id', 'balance'])
            ->where('user_id=:userId', [':userId' => $userId])
            ->one();
        $condition = 'user_id =:userId';
        $params = array(':userId' => $userId);
        $data = AccountLog::find()->where($condition, $params);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $logList = $data->select(['amount', 'amount_log', 'time', 'note'])
            ->where($condition, $params)
            ->orderBy('id desc')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return $this->render('account', array('pages' => $pages, 'logList' => $logList, 'memberInfo' => $memberInfo));
    }

    /**
     * 我的收藏
     */
    public function actionFavorite() {
        $this->is_login();
        $this->currentMenu = 3;
        $userId = $this->data['shopUserInfo']['userId'];
        $condition = 'user_id =:userId';
        $params = array(':userId' => $userId);
        $data = Favorite::find()->where($condition, $params);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $favoriteList = $data->select(['id', 'user_id', 'rid', 'time'])
            ->where($condition, $params)
            ->orderBy('id desc')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return $this->render('favorite', array('pages' => $pages, 'favoriteList' => $favoriteList));
    }

    /**
     * 地址管理
     */
    public function actionAddress() {
        $this->is_login();
        $this->currentMenu = 4;
        $userId = $this->data['shopUserInfo']['userId'];
        $condition = 'user_id =:userId';
        $params = array(':userId' => $userId);
        $data = Address::find()->where($condition, $params);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $addressList = $data->select(['id', 'user_id', 'accept_name', 'province', 'city', 'area', 'address', 'mobile', 'is_default'])
            ->where($condition, $params)
            ->orderBy('id desc')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return $this->render('address', array('pages' => $pages, 'addressList' => $addressList));
    }

    /**
     * 个人资料
     */
    public function actionInfo() {
        $this->is_login();
        $this->currentMenu = 5;
        $userId = $this->data['shopUserInfo']['userId'];
        $memberModel = new Member();
        $resultArr = array();
        if (Yii::$app->request->post()) {
            $data['true_name'] = Yii::$app->request->post('true_name');
            $data['sex'] = Yii::$app->request->post('sex');
            $data['birthday'] = Yii::$app->request->post('birthday');
            $province = Yii::$app->request->post('province');
            $city = Yii::$app->request->post('city');
            $area = Yii::$app->request->post('area');
            $data['area'] = ',' . $province . ',' . $city . ',' . $area . ',';
            $data['contact_addr'] = Yii::$app->request->post('contact_addr');
            $data['mobile'] = Yii::$app->request->post('mobile');
            $data['email'] = Yii::$app->request->post('email');
            $data['qq'] = Yii::$app->request->post('qq');
            $result = $memberModel->updateAll($data, 'user_id=:userId', [':userId' => $userId]);
            if ($result) {
                $resultArr = ['errcode' => 0, 'errmsg' => '编辑成功'];
            } else {
                $resultArr = ['errcode' => 1, 'errmsg' => '编辑失败'];
            }
        }
        $userModel = new User();
        $userInfo = $userModel->find()
            ->select('username')
            ->where('id=:userId', [':userId' => $userId])
            ->one();
        $memberInfo = $memberModel->find()
            ->where('user_id=:userId', [':userId' => $userId])
            ->one();
        return $this->render('info', array('userInfo' => $userInfo, 'memberInfo' => $memberInfo, 'resultArr' => $resultArr));
    }

    /**
     * 修改密码
     */
    public function actionPassword() {
        $this->is_login();
        $this->currentMenu = 6;
        $userId = $this->data['shopUserInfo']['userId'];
        $userModel = new User();
        $resultArr = array();
        if (Yii::$app->request->post()) {
            $oldPwd = Yii::$app->request->post('old_pwd');
            $password = Yii::$app->request->post('password');
            $rePassword = Yii::$app->request->post('repassword');
            if ($password != $rePassword) {
                $resultArr = ['errcode' => 1, 'errmsg' => '两次输入密码不一致'];
            } else {
                //查询用户信息
                $userInfo = $userModel->find()
                    ->select(['id', 'password', 'salt'])
                    ->where('id=:userId', [':userId' => $userId])
                    ->one();
                $newPassword = CommonTools::getPwd($oldPwd, $userInfo['salt']);
                //校验原密码
                if ($newPassword != $userInfo['password']) {
                    $resultArr = ['errcode' => 1, 'errmsg' => '原密码错误'];
                } else {
                    //生成并保存新密码
                    $salt = uniqid();
                    $userInfo->password = CommonTools::getPwd($password, $salt);
                    $userInfo->salt = $salt;
                    $result = $userInfo->update();
                    if ($result) {
                        $resultArr = ['errcode' => 0, 'errmsg' => '修改成功'];
                    } else {
                        $resultArr = ['errcode' => 1, 'errmsg' => '修改失败'];
                    }
                }
            }
        }
        return $this->render('password', ['resultArr' => $resultArr]);
    }

    /**
     * 地址编辑
     */
    public function actionAddressEdit() {
        $this->is_login();
        $addressId = Yii::$app->request->post('id');
        $data['accept_name'] = Yii::$app->request->post('accept_name');
        $data['province'] = intval(Yii::$app->request->post('province'));
        $data['city'] = intval(Yii::$app->request->post('city'));
        $data ['area'] = intval(Yii::$app->request->post('area'));
        $data['address'] = Yii::$app->request->post('address');
        $data['mobile'] = Yii::$app->request->post('mobile');
        $isDefault = Yii::$app->request->post('is_default');
        $data['is_default'] = empty($isDefault) ? 0 : 1;
        $userId = $this->data['shopUserInfo']['userId'];
        $addressData = array();
        $addressModel = new Address();
        if ($isDefault) {
            $addressModel->updateAll(['is_default' => 0], 'user_id=:userId', [':userId' => $userId]);
        }
        foreach ($data as $key => $value) {
            $addressModel->$key = $value;
            $addressData[$key] = $value;
        }
        if ($userId) {
            if ($addressId) {
                $addressModel->updateAll($data, 'id=:addressId and user_id=:userId', array(':addressId' => $addressId, ':userId' => $userId));
                $addressData['id'] = $addressId;
            } else {
                $addressModel->user_id = $userId;
                $addressModel->save();
                $addressData['id'] = $addressModel->id;
            }
        }
        $this->redirect(Url::to(['/ucenter/address']));
    }

    /**
     * 设置默认的收货地址
     */
    public function actionAddressDefault() {
        $this->is_login();
        $addressId = intval(Yii::$app->request->get('id'));
        $default = intval(Yii::$app->request->get('is_default'));
        $userId = $this->data['shopUserInfo']['userId'];
        $model = new Address();
        if ($default == 1) {
            $model->updateAll(['is_default' => 0], 'user_id=:userId', [':userId' => $userId]);
        }
        $model->updateAll(['is_default' => $default], 'id=:addressId and user_id=:userId', [':addressId' => $addressId, ':userId' => $userId]);
        $this->redirect(Url::to(['/ucenter/address']));
    }

    /**
     * 收货地址删除处理
     */
    public function actionAddressDel() {
        $addressId = intval(Yii::$app->request->get('id'));
        $userId = $this->data['shopUserInfo']['userId'];
        $model = new Address();
        $result = array('result' => false, 'msg' => '删除失败，请稍后重试');
        $info = $model->find()
            ->where('id=:addressId and user_id=:userId', [':addressId' => $addressId, ':userId' => $userId])
            ->one();
        if ($info) {
            $flag = $info->delete();
            if ($flag) {
                $result = array('result' => true, 'msg' => '删除成功');
            }
        }
        echo json_encode($result);
    }

    /**
     * 取消收藏
     */
    public function actionFavoriteDel() {
        $favoriteIds = intval(Yii::$app->request->post('ids'));
        $userId = $this->data['shopUserInfo']['userId'];
        if(empty($userId) || empty($favoriteIds)){
            return json_encode(['errcode' => 1, 'errmsg' => '取消失败']);
        }
        $model = new Favorite();
        $result = $model->deleteAll('id=:favoriteId and user_id=:userId', [':favoriteId' => $favoriteIds, ':userId' => $userId]);
        if ($result) {
            return json_encode(['errcode' => 0, 'errmsg' => '成功取消']);
        } else {
            return json_encode(['errcode' => 1, 'errmsg' => '取消失败']);
        }
    }

}
