<?php

/*
 * 品牌分类模型
 */

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Description of BrandCategory
 *
 * @author baihua <baihua_2011@163.com>
 */
class BrandCategory extends ActiveRecord {

    /**
     * 获取品牌数据
     * @return type
     */
    public static function getAllList() {
        return self::find()->select(['id', 'name'])->all();
    }

    /**
     * 对应表名
     * @return string
     */
//    public static function tableName() {
//        return '{{%brand_category}}';
//    }
}
