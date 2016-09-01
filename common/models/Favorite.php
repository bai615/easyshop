<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * 收藏夹表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Favorite extends ActiveRecord {

    /**
     * 获取一个收藏对应的商品
     * @return type
     */
    public function getGoods() {
        // 第一个参数为要关联的子表模型类名，
        // 第二个参数指定 通过子表的rid，关联主表的id字段
        return $this->hasOne(Goods::className(), ['id' => 'rid']);
    }

}
