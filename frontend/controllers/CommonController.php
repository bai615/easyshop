<?php

namespace frontend\controllers;

use Yii;
use common\models\Areas;
use common\models\Order;
use common\utils\CommonTools;
use frontend\logics\PayLogic;
use common\models\Payment;

/**
 * 前台公共模块
 *
 * @author baihua <baihua_2011@163.com>
 */
class CommonController extends BaseController {

    /**
     * 成功提示页
     */
    public function actionSuccess() {
        $data['message'] = Yii::$app->request->get('message');
        return $this->render('success', $data);
    }

    /**
     * 获取地区
     */
    public function actionAreaChild() {
        $parentId = intval(Yii::$app->request->get('aid'));
        $areaModel = new Areas();
        $areaList = $areaModel->find()
            ->select(['area_id', 'parent_id', 'area_name', 'sort'])
            ->where('parent_id=:parentId', [':parentId' => $parentId])
            ->orderBy('sort asc')
            ->all();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($areaList) {
            return ($areaList);
        }
    }

    /**
     * 进行支付支付方法
     */
    public function actionDoPay() {
        $this->is_login();
        $orderId = intval(Yii::$app->request->get('order_id'));
        $paymentId = intval(Yii::$app->request->post('payment_id'));
        $recharge = Yii::$app->request->post('recharge');
        if ($orderId) {
            //获取订单信息
            $orderObj = new Order();
            $orderInfo = $orderObj->find()
                ->select(['pay_type'])
                ->where('id=:orderId', [':orderId' => $orderId])
                ->one();
            if (empty($orderInfo)) {
                CommonTools::showWarning('要支付的订单信息不存在');
            }
            $paymentId = $orderInfo['pay_type'];
        }
        //获取支付方式类库
        $paymentInstance = PayLogic::createPaymentInstance($paymentId);
        //在线充值
        if ($recharge) {
            $paymentInfo = Payment::getPaymentById($paymentId);
            $reData = ['account' => $recharge, 'paymentName' => $paymentInfo['name']];
            $sendData = $paymentInstance->getSendData(PayLogic::getPaymentInfo($paymentId, 'recharge', $reData));
        }
        //订单支付
        else if ($orderId) {
            $sendData = $paymentInstance->getSendData(PayLogic::getPaymentInfo($paymentId, 'order', $orderId));
        } else {
            CommonTools::showWarning('发生支付错误');
        }
        $paymentInstance->doPay($sendData);
    }

}
