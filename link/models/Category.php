<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace link\models;

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
        return ArrayHelper::toArray(self::find()->orderBy('sort asc,id asc')->all());
    }

}
