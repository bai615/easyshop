<?php

namespace frontend\controllers;

use Yii;
use common\utils\CommonTools;
use common\models\Payment;
use common\models\Member;
use common\models\Order;
use frontend\models\AccountLog;
use frontend\logics\OrderLogic;
/**
 * 用户中心模块
 *
 * @author baihua <baihua_2011@163.com>
 */
class UcenterController extends BaseController {

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
            ->where('user_id=:userId',[':userId' => $userId])
            ->one();
        if (empty($memberInfo)) {
            CommonTools::showWarning('用户信息不存在');
        }
        //获取订单信息
        $orderObj = new Order();
        $orderInfo = $orderObj->find()
            ->where('order_no=:orderNo and pay_status = 0 and status = 1 and user_id=:userId',[':orderNo' => $return['order_no'], ':userId' => $userId])
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

}
