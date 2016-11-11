<?php

namespace frontend\logics;

use Yii;
use common\utils\CommonTools;
use common\models\Payment;
use common\models\Order;
use common\models\OrderGoods;
use common\models\OnlineRecharge;
use frontend\models\AccountLog;

/**
 * 支付方式 操作类
 *
 * @author baihua <baihua_2011@163.com>
 */
class PayLogic {

    /**
     * 创建支付类实例
     * @param $paymentId int 支付方式ID
     * @return 返回支付插件类对象
     */
    public static function createPaymentInstance($paymentId) {
        $paymentInfo = Payment::getPaymentById($paymentId);
        if ($paymentInfo && isset($paymentInfo['class_name']) && $paymentInfo['class_name']) {
            $className = $paymentInfo['class_name'];
            $classPath = Yii::$app->basePath . DIRECTORY_SEPARATOR . "payments" . DIRECTORY_SEPARATOR . $className . '.php';
            if (file_exists($classPath)) {
                $payClass = 'frontend\payments\\' . $className;
                return new $payClass($paymentId);
            } else {
                CommonTools::showWarning('支付接口类' . $className . '没有找到');
            }
        } else {
            CommonTools::showWarning('支付方式不存在');
        }
    }

    /**
     * @brief 获取订单中的支付信息 M:必要信息; P表示用户;
     * @param $paymentId int    支付方式ID
     * @param $type       string 信息获取方式 order:订单支付;recharge:在线充值;
     * @param $argument   mix    参数
     * @return array 支付提交信息
     */
    public static function getPaymentInfo($paymentId, $type, $argument) {

        //初始化配置参数
        $paymentInstance = PayLogic::createPaymentInstance($paymentId);
        $payment = $paymentInstance->configParam();

        //获取公共信息
        $paymentInfo = Payment::getPaymentById($paymentId, 'config_param');
        if ($paymentInfo) {
            $paymentInfo = json_decode($paymentInfo, true);
            foreach ($paymentInfo as $key => $item) {
                $payment[$key] = $item;
            }
        }

        if ('order' == $type) {
            //获取订单信息
            $orderObj = new Order();
            $orderInfo = $orderObj->find()
                ->select(['id', 'order_no', 'order_amount', 'postscript', 'mobile', 'accept_name', 'address'])
                ->where('id=:orderId  and status = 1', [':orderId' => $argument])
                ->one();
            if (empty($orderInfo)) {
                CommonTools::showWarning('订单信息不正确，不能进行支付');
            }
            //判断商品库存
            $orderGoodsObj = new OrderGoods();
            $orderGoodsList = $orderGoodsObj->find()
                ->select(['goods_id', 'goods_name', 'product_id', 'goods_nums'])
                ->where('order_id=:orderId', [':orderId' => $argument])
                ->all();
            $products = '';
            foreach ($orderGoodsList as $key => $val) {
                if (!GoodsLogic::checkStore($val['goods_nums'], $val['goods_id'], $val['product_id'])) {
                    CommonTools::showWarning('商品库存不足无法支付，请重新下单');
                }
                $products = $products . '@@@' . $val['goods_name'];
            }
            $payment['products'] = trim($products, '@@@');
            $payment['M_Remark'] = $orderInfo['postscript'];
            $payment['M_OrderId'] = $orderInfo['id'];
            $payment['M_OrderNO'] = $orderInfo['order_no'];
            $payment['M_Amount'] = $orderInfo['order_amount'];

            //用户信息
            $payment['P_Mobile'] = $orderInfo['mobile'];
            $payment['P_Name'] = $orderInfo['accept_name'];
            $payment['P_Address'] = $orderInfo['address'];
        } else if ('recharge' == $type) {
            if (!isset($argument['account']) || $argument['account'] <= 0) {
                CommonTools::showWarning('请填入正确的充值金额');
            }
            $rechargeNo = OrderLogic::createOrderNo();
            $rechargeModel = new OnlineRecharge();
            $rechargeModel->user_id = Yii::$app->controller->data['shopUserInfo']['userId'];
            $rechargeModel->recharge_no = $rechargeNo;
            $rechargeModel->account = $argument['account'];
            $rechargeModel->time = date('Y-m-d H:i:s');
            $rechargeModel->payment_name = $argument['paymentName'];
            $rechargeModel->save();
            $rechargeId = $rechargeModel->id;
            //充值数据
            $payment['products'] = '在线充值';
            $payment['M_OrderNO'] = 'recharge' . $rechargeNo;
            $payment['M_OrderId'] = $rechargeId;
            $payment['M_Amount'] = $argument['account'];
        }

        //交易信息
        $payment['M_Time'] = time();
        $payment['M_Paymentid'] = $paymentId;
        return $payment;
    }

    /**
     * 更新在线充值
     * @param type $rechargeNo
     */
    public static function updateRecharge($rechargeNo) {
        $rechargeModel = new OnlineRecharge();
        $rechargeInfo = $rechargeModel->find()->where('recharge_no = :rechargeNo', [':rechargeNo' => $rechargeNo])->one();
        //充值信息不存在
        if (empty($rechargeInfo)) {
            return false;
        }
        //已充值成功
        if ($rechargeInfo['status'] == 1) {
            return true;
        }

        $result = $rechargeModel->updateAll(['status' => 1], 'recharge_no = :rechargeNo', [':rechargeNo' => $rechargeNo]);
        if ($result) {
            $log = new AccountLog();
            $config = array(
                'user_id' => $rechargeInfo['user_id'],
                'event' => 'recharge',
                'note' => '用户在线充值',
                'amount' => $rechargeInfo['account'],
            );
            return $log->write($config);
        }else{
            return false;
        }
    }

}
