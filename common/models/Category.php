<?php

/*
 * 产品分类模型
 */

namespace common\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Description of Category
 *
 * @author baihua <baihua_2011@163.com>
 */
class Category extends ActiveRecord {

    /**
     * 获取所有分类
     * @return type
     */
    public static function getAll() {
        return ArrayHelper::toArray(self::find()->orderBy('sort asc')->all());
    }

    /**
     * 对应表名
     * @return string
     */
//    public static function tableName() {
//        return '{{%category}}';
//    }
}
