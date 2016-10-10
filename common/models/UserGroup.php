<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * Description of UserGroup
 *
 * @author baihua <baihua_2011@163.com>
 */
class UserGroup extends ActiveRecord {

    /**
     * 获取所有用户组
     * @return type
     */
    public static function getAll() {
        return ArrayHelper::toArray(self::find()->orderBy('id desc')->all());
    }

}
