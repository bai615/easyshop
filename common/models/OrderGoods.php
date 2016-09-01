<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * 订单商品表
 *
 * @author baihua <baihua_2011@163.com>
 */
class OrderGoods extends ActiveRecord {

    /**
     * 获取一个产品对应的商品
     * @return type
     */
    public function getOrders() {
        // 第一个参数为要关联的子表模型类名，
        // 第二个参数指定 通过子表的order_id，关联主表的id字段
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

}
