<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * 支付方式表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Payment extends ActiveRecord {

    /**
     * 根据支付方式配置编号  获取该插件的详细配置信息
     * @param $paymentId int    支付方式ID
     * @param $key        string 字段
     * @return 返回支付插件类对象
     */
    public static function getPaymentById($paymentId, $key = '') {
        $info = Payment::find()->where('id=:paymentId',[':paymentId'=>$paymentId])->one();
        if ($key) {
            return isset($info[$key]) ? $info[$key] : '';
        }
        return $info;
    }

    /**
     * 根据支付方式配置编号  获取该插件的配置信息
     * @param $payment_id int    支付方式ID
     * @param $key        string 字段
     * @return 返回支付插件类对象
     */
    public static function getConfigParam($payment_id, $key = '') {
        $payConfig = self::getPaymentById($payment_id, 'config_param');
        if ($payConfig) {
            $payConfig = json_decode($payConfig, true);
            return isset($payConfig[$key]) ? $payConfig[$key] : '';
        }
        return '';
    }

    /**
     * 获取支付方式
     * @return type
     */
    public static function getPaymentList() {
        return self::find()->where('status = 0')->orderBy('`order` asc')->all();
    }

    /**
     * 线上充值的支付方式
     * @return type
     */
    public static function getPaymentListByOnline() {
        return self::find()->where(" type = 1 and status = 0 and class_name not in ('BalancePay') and client_type in(1,3)")->orderBy('`order` asc')->all();
    }

}
