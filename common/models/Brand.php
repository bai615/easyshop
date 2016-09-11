<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * 品牌模型
 *
 * @author baihua <baihua_2011@163.com>
 */
class Brand extends ActiveRecord {

    /**
     * 获取品牌数据
     * @return type
     */
    public static function getAllList() {
        return self::find()->select(['id', 'name'])->all();
    }

}
