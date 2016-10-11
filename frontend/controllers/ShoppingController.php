<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Json;
use common\models\Address;
use common\models\Payment;
use common\models\Areas;
use common\models\Order;
use frontend\models\CountSum;
use frontend\logics\OrderLogic;
use frontend\logics\CartLogic;

/**
 * Description of ShoppingController
 *
 * @author baihua <baihua_2011@163.com>
 */
class ShoppingController extends BaseController {

    /**
     * 订单信息核对确认
     */
    public function actionConfirm() {
        $this->is_login();
        $userId = $this->data['shopUserInfo']['userId'];
        $id = intval(Yii::$app->request->get('id'));
        $buyNum = intval(Yii::$app->request->get('num'));
        $type = Yii::$app->request->get('type');

        $addressList = Address::getAddress($userId);
        $paymentList = Payment::getPaymentList();

        //计算商品
        $countSumObj = new CountSum();
        $cartInfo = $countSumObj->cartCount($id, $type, $buyNum);

        $data['gid'] = $id;
        $data['type'] = $type;
        $data['addressList'] = $addressList;
        $data['paymentList'] = $paymentList;
        $data['cartInfo'] = $cartInfo;
        return $this->render('confirm', $data);
    }

    /**
     * 收货地址弹出框
     */
    public function actionAddress() {
        $this->is_login();
        $userId = $this->data['shopUserInfo']['userId'];
        $addressId = intval(Yii::$app->request->get('id'));
        $addressRow = array();
        if ($userId && $addressId) {
            $model = new Address();
            $addressRow = $model->find()
                ->where('id=:addressId and user_id=:userId', [':addressId' => $addressId, ':userId' => $userId])
                ->one();
        }
        return $this->renderPartial('address', array('addressRow' => $addressRow));
    }

    /**
     * 添加地址
     */
    public function actionAddressAdd() {
        $this->is_login();
        $addressId = Yii::$app->request->get('id');
        $data['accept_name'] = Yii::$app->request->get('accept_name');
        $data['province'] = intval(Yii::$app->request->get('province'));
        $data['city'] = intval(Yii::$app->request->get('city'));
        $data ['area'] = intval(Yii::$app->request->get('area'));
        $data['address'] = Yii::$app->request->get('address');
        $data['mobile'] = Yii::$app->request->get('mobile');
        $userId = $this->data['shopUserInfo']['userId'];
        $addressData = array();
        $addressModel = new Address();
        foreach ($data as $key => $value) {
            if (!$value) {
                $result = array('result' => false, 'msg' => '请仔细填写收货地址');
                die(json_encode($result));
            }
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
            $areaList = Areas::name($data['province'], $data['city'], $data ['area']);
            $addressData['province_val'] = $areaList[$data['province']];
            $addressData['city_val'] = $areaList[$data['city']];
            $addressData['area_val'] = $areaList[$data ['area']];
            $result = array('data' => $addressData);
        } else {
            $result = array('result' => false, 'msg' => '添加失败，请稍后重试');
        }
        die(json_encode($result));
    }

    /**
     * 订单生成
     */
    public function actionOrder() {
        $this->is_login();
        $gid = intval(Yii::$app->request->post('direct_gid'));
        $buyNum = intval(Yii::$app->request->post('direct_num'));
        $type = Yii::$app->request->post('direct_type');
        $addressId = intval(Yii::$app->request->post('radio_address'));
        $paymentId = intval(Yii::$app->request->post('radio_payment'));
        $message = Yii::$app->request->post('message');
        $userId = $this->data['shopUserInfo']['userId'];

        //计算商品
        $countSumObj = new CountSum();
        $cartInfo = $countSumObj->cartCount($gid, $type, $buyNum);

        //处理收件地址
        $addressModel = new Address();
        $addressInfo = $addressModel->find()
            ->where('id=:addressId and user_id=:userId', [':addressId' => $addressId, ':userId' => $userId])
            ->one();
        if (empty($addressInfo)) {
            die('<script>alert("收货地址信息错误");window.history.go(-1);</script>');
        }

        //检查订单重复
        $checkData = array(
            "accept_name" => $addressInfo['accept_name'],
            "address" => $addressInfo['address'],
            "mobile" => $addressInfo['mobile'],
        );

        //检查订单重复
        $result = OrderLogic::checkRepeat($checkData, $cartInfo['goodsList']);
        if (is_string($result)) {
            die('<script>alert("' . $result . '");window.history.go(-1);</script>');
        }
        //处理支付方式
        $paymentObj = new Payment();
        $paymentInfo = $paymentObj->find()
            ->select(['name', 'type'])
            ->where('id=:paymentId', [':paymentId' => $paymentId])
            ->one();
        if (empty($paymentInfo)) {
            die('<script>alert("支付方式错误");window.history.go(-1);</script>');
        }

        //生成的订单数据
        $orderObj = new Order();
        $orderObj->order_no = OrderLogic::createOrderNo();
        $orderObj->user_id = $userId;
        $orderObj->accept_name = $addressInfo['accept_name'];
        $orderObj->pay_type = $paymentId;
        $orderObj->province = $addressInfo['province'];
        $orderObj->city = $addressInfo['city'];
        $orderObj->area = $addressInfo['area'];
        $orderObj->address = $addressInfo['address'];
        $orderObj->mobile = $addressInfo['mobile'];
        $orderObj->create_time = date('Y-m-d H:i:s');
        $orderObj->postscript = $message;
        //商品价格
        $orderObj->payable_amount = $cartInfo['sum'];
        $orderObj->real_amount = $cartInfo['final_sum'];
        //订单应付总额
        $orderObj->order_amount = $cartInfo['final_sum'];
        //备注信息
        $orderObj->note = '';
        $orderObj->save();
        $orderId = $orderObj->id;

        //将订单中的商品插入到order_goods表
        $orderInstance = new OrderLogic();
        $orderInstance->insertOrderGoods($orderId, $cartInfo['goodsList']);

        $data['orderId'] = $orderId;
        $data['orderNo'] = $orderObj->order_no;
        $data['orderAmount'] = sprintf('%.2f', $orderObj->order_amount);
        $data['paymentInfo'] = $paymentInfo;
        return $this->render('order', $data);
    }

    /**
     * 商品加入购物车[ajax]
     */
    function actionJoinCart() {
        $goodsId = intval(Yii::$app->request->post('goods_id'));
        $goodsNum = Yii::$app->request->post('goods_num');
        $goodsNum = empty($goodsNum) ? 1 : intval($goodsNum);
        $type = Yii::$app->request->post('type');

        //加入购物车
        $cartLogic = new CartLogic();
        $addResult = $cartLogic->add($goodsId, $goodsNum, $type);
        if ($addResult === false) {
            $result = array(
                'errcode' => 1,
                'errmsg' => $cartLogic->getError(),
            );
        } else {
            $result = array(
                'errcode' => 0,
                'errmsg' => '添加成功',
            );
        }
        echo Json::encode($result);
    }
    
    /**
     * 购物车div展示
     */
    public function actionShowCart(){
        $cartLogic = new CartLogic();
    	$cartList = $cartLogic->getMyCart();
    	$data['data'] = array_merge($cartList['goods']['data'],$cartList['product']['data']);
    	$data['count']= $cartList['count'];
    	$data['sum']  = $cartList['sum'];
    	echo Json::encode($data);
    }
    
    
    public function actionCart(){
        
    }

}
