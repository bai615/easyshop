<?php

/*
 * 商品模型
 */

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Description of Goods
 *
 * @author baihua <baihua_2011@163.com>
 */
class Goods extends ActiveRecord {

    public $goods_id;
    public $brand_name; //商品品牌名称
    public $category_id; //分类ID
    public $photo; //商品图片集
    public $price_area; //价格区间

    /**
     * 获取一个商品对应的所有产品
     * 一对多的关联，一个商品有多个不同规格的产品
     * @return type
     */
    public function getProducts() {
        // 第一个参数为要关联的子表模型类名，
        // 第二个参数指定 通过子表的goods_id，关联主表的id字段
        return $this->hasMany(Products::className(), ['goods_id' => 'id']);
    }
    
    /**
     * 获取一个商品对应的所有收藏
     * @return type
     */
    public function getFavorite() {
        return $this->hasMany(Favorite::className(), ['rid' => 'id']);
    }

}
