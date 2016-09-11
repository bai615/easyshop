<?php

/*
 * 模型表
 */

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Description of Model
 *
 * @author baihua <baihua_2011@163.com>
 */
class Model extends ActiveRecord {
    
    /**
     * 获取模型数据
     * @return type
     */
    public static function getModelList(){
        return self::find()->select(['id','name'])->all();
    }
}
