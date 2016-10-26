<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use common\utils\CommonTools;
use frontend\logics\PayLogic;
use frontend\logics\OrderLogic;

/**
 * Description of PayController
 *
 * @author baihua <baihua_2011@163.com>
 */
class PayController extends BaseController {

    /**
     * 同步信息返回
     */
    public function actionCallback() {
        //从URL中获取支付方式
        $paymentId = intval(Yii::$app->request->get('_id'));
        $paymentInstance = PayLogic::createPaymentInstance($paymentId);
        if (!is_object($paymentInstance)) {
            CommonTools::showWarning('支付方式不存在');
        }
        //初始化参数
        $money = '';
        $message = '支付失败';
        $orderNo = '';
        //执行接口回调函数
        $callbackData = array_merge($_POST, $_GET);
        unset($callbackData['_id']);
        $return = $paymentInstance->callback($callbackData, $paymentId, $money, $message, $orderNo);
        if (true == $return) {
            $orderId = OrderLogic::updateOrderStatus($orderNo);
            if (false == $orderId) {
                CommonTools::showWarning($message);
            } else {
                //支付成功
                $this->redirect(Url::to(['common/success', 'message' => '支付成功']));
            }
        } else {
            //支付失败
            $message = $message ? $message : '支付失败';
            CommonTools::showWarning($message);
        }
    }

    /**
     * 异步信息通知
     */
    public function actionNotify() {
        
    }

}
