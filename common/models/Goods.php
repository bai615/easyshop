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

    public $brand_name; //商品品牌名称
    public $category_id; //分类ID
    public $photo; //商品图片集
    public $price_area; //价格区间

}
