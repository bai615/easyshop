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

    public $floor; //列表缩进层次

    /**
     * 获取所有分类
     * @return type
     */
    public static function getAll() {
        return ArrayHelper::toArray(self::find()->orderBy('sort asc')->all());
    }
    
    /**
     * 获取单个信息
     */
    public static function getOneInfo($categoryId){
        $model = new Category();
        $info = $model->find()->where('id=:categoryId',[':categoryId'=>$categoryId])->one();
        return ArrayHelper::toArray($info);
    }
    
    /**
     * 通过ID获取名字
     */
    public static function getNameById($categoryId){
        $model = new Category();
        $info = $model->find()->where('id=:categoryId',[':categoryId'=>$categoryId])->one();
        return empty($info) ? '' : $info['name'];
    }

    /**
     * 对应表名
     * @return string
     */
//    public static function tableName() {
//        return '{{%category}}';
//    }
}
