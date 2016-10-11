<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\models;

use yii\db\ActiveRecord;

/**
 * Description of Cart
 *
 * @author baihua <baihua_2011@163.com>
 */
class Cart extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%goods_car}}';
    }

}
