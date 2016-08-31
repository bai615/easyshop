<?php

namespace common\models;

use yii\db\ActiveRecord;
/**
 * 订单表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Order extends ActiveRecord {

    /**
     * 获取订单状态
     * @param $orderRow array('status' => '订单状态','pay_type' => '支付方式ID','distribution_status' => '配送状态','pay_status' => '支付状态')
     * @return int 订单状态值 0:未知; 1:未付款等待发货(货到付款); 2:等待付款(线上支付); 3:已发货(已付款); 4:已付款等待发货; 5:已取消; 6:已完成(已付款,已收货); 7:已退款; 8:部分发货(不需要付款); 9:部分退款(未发货+部分发货); 10:部分退款(已发货); 11:已发货(未付款);
     */
    public static function getOrderStatus($orderRow) {
        //1,刚生成订单,未付款
        if ($orderRow['status'] == 1) {
            //选择货到付款
            if ($orderRow['pay_type'] == 0) {
                if ($orderRow['distribution_status'] == 0) {
                    return 1;
                } else if ($orderRow['distribution_status'] == 1) {
                    return 11;
                } else if ($orderRow['distribution_status'] == 2) {
                    return 8;
                }
            }
            //选择在线支付
            else {
                return 2;
            }
        }
        //2,已经付款
        else if ($orderRow['status'] == 2) {
            $refundModel = new RefundmentDoc();
            $refundRow = $refundModel->find(array(
                'condition' => 'order_no=:orderNo and if_del=0 and pay_status=0',
                'params' => array('orderNo' => $orderRow['order_no'])
            ));
            if ($refundRow) {
                return 12;
            }

            if ($orderRow['distribution_status'] == 0) {
                return 4;
            } else if ($orderRow['distribution_status'] == 1) {
                return 3;
            } else if ($orderRow['distribution_status'] == 2) {
                return 8;
            }
        }
        //3,取消或者作废订单
        else if ($orderRow['status'] == 3 || $orderRow['status'] == 4) {
            return 5;
        }
        //4,完成订单
        else if ($orderRow['status'] == 5) {
            return 6;
        }
        //5,退款
        else if ($orderRow['status'] == 6) {
            return 7;
        }
        //6,部分退款
        else if ($orderRow['status'] == 7) {
            //发货
            if ($orderRow['distribution_status'] == 1) {
                return 10;
            }
            //未发货
            else {
                return 9;
            }
        }
        return 0;
    }

    //获取订单支付状态
    public static function getOrderPayStatusText($orderRow) {
        if ($orderRow['status'] == '6') {
            return '全部退款';
        }

        if ($orderRow['status'] == '7') {
            return '部分退款';
        }

        if ($orderRow['pay_status'] == 0) {
            return '未付款';
        }

        if ($orderRow['pay_status'] == 1) {
            return '已付款';
        }
        return '未知';
    }

    /**
     * 获取订单状态问题说明
     * @param $statusCode int 订单的状态码
     * @return string 订单状态说明
     */
    public static function orderStatusText($statusCode) {
        $result = array(
            0 => '未知',
            1 => '等待发货',
            2 => '等待付款',
            3 => '已发货',
            4 => '等待发货',
            5 => '已取消',
            6 => '已完成',
            7 => '已退款',
            8 => '部分发货',
            9 => '部分发货',
            10 => '部分退款',
            11 => '已发货',
            12 => '申请退款',
        );
        return isset($result[$statusCode]) ? $result[$statusCode] : '';
    }

}
