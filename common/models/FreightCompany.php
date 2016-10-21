<?php

/*
 * 货运公司
 */

namespace common\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Description of Goods
 *
 * @author baihua <baihua_2011@163.com>
 */
class FreightCompany extends ActiveRecord {

    /**
     * 获取所有货运公司
     * @return type
     */
    public static function getAll() {
        return ArrayHelper::toArray(self::find()->where('is_del=0')->orderBy('sort desc')->all());
    }

}
