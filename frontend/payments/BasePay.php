<?php

namespace frontend\payments;

use yii\helpers\Url;
use common\models\Order;

/**
 * 支付基础类
 *
 * @author baihua <baihua_2011@163.com>
 */
abstract class BasePay {

    public $method = "post"; //表单提交模式
    public $callbackUrl = '';    //支付完成后，同步回调地址
    public $serverCallbackUrl = '';    //异步通知地址

    /**
     * 构造函数
     * @param $paymentId 支付方式ID
     */

    public function __construct($paymentId) {
        //回调函数地址
        $this->callbackUrl = Url::toRoute(['/pay/callback', '_id' => $paymentId], true);
        //回调业务处理地址
        $this->serverCallbackUrl = Url::toRoute(['/pay/notify', '_id' => $paymentId], true);
    }

    /**
     * 记录支付平台的交易号
     * @param $orderNo string 订单编号
     * @param $tradeNo string 交易流水号
     * @return boolean
     */
    protected function recordTradeNo($orderNo, $tradeNo) {
        $orderModel = new Order();
        $orderInfo = $orderModel->find()
            ->select(['id', 'trade_no'])
            ->where('order_no=:orderNo', [':orderNo' => $orderNo])
            ->one();
        $orderInfo->trade_no = $tradeNo;
        $orderInfo->update();
    }

    /**
     * 开始支付
     */
    public function doPay($sendData) {

        $data['submitUrl'] = $this->getSubmitUrl();
        $data['method'] = $this->method;
        $data['sendData'] = $sendData;
        echo \Yii::$app->controller->renderPartial('pay_view', $data);
//        echo <<< OEF
//		<!DOCTYPE html>
//        <html lang="en">
//			<head></head>
//			<body>
//				<p>please wait...</p>
//				<form action="{$this->getSubmitUrl()}" method="{$this->method}">
//OEF;
//        foreach ($sendData as $key => $item) {
//            echo <<< OEF
//					<input type='hidden' name='{$key}' value='{$item}' />
//OEF;
//        }
//        echo <<< OEF
//				</form>
//			</body>
//			<script type='text/javascript'>
//				window.document.forms[0].submit();
//			</script>
//		</html>
//OEF;
    }

    /**
     * 返回配置参数
     */
    public function configParam() {
        return array(
            'M_PartnerId' => '', //商户ID号
            'M_PartnerKey' => '', //商户KEY密钥
        );
    }

    /**
     * 获取提交地址
     * @return string Url提交地址
     */
    abstract public function getSubmitUrl();

    /**
     * 获取要发送的数据数组结构
     * @param $paymentInfo array 要传递的支付信息
     * @return array
     */
    abstract public function getSendData($paymentInfo);

    /**
     * 同步支付回调
     * @param $ExternalData array  支付接口回传的数据
     * @param $paymentId    int    支付接口ID
     * @param $money        float  交易金额
     * @param $message      string 信息
     * @param $orderNo      string 订单号
     */
    abstract public function callback($ExternalData, &$paymentId, &$money, &$message, &$orderNo);

    /**
     * 同步支付回调
     * @param $ExternalData array  支付接口回传的数据
     * @param $paymentId    int    支付接口ID
     * @param $money        float  交易金额
     * @param $message      string 信息
     * @param $orderNo      string 订单号
     */
    abstract public function serverCallback($ExternalData, &$paymentId, &$money, &$message, &$orderNo);
}
