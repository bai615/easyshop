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
    public static function getOneInfo($categoryId) {
        $model = new Category();
        $info = $model->find()->where('id=:categoryId', [':categoryId' => $categoryId])->one();
        return ArrayHelper::toArray($info);
    }

    /**
     * 通过ID获取名字
     */
    public static function getNameById($categoryId) {
        $model = new Category();
        $info = $model->find()->where('id=:categoryId', [':categoryId' => $categoryId])->one();
        return empty($info) ? '' : $info['name'];
    }

    /**
     * 获取子分类可以无限递归获取子分类
     * @param int $catId 分类ID
     * @param int $level 层级数
     * @return string 所有分类的ID拼接字符串
     */
    public static function getChild($catId, $level = 1) {
        if ($level == 0) {
            return $catId;
        }

        $temp = array();
        $result = array($catId);

        while (true) {
            $id = current($result);
            if (!$id) {
                break;
            }
            $temp = self::find()->where('parent_id = :catId', [':catId' => $id])->all();
            foreach ($temp as $key => $val) {
                if (!in_array($val['id'], $result)) {
                    $result[] = $val['id'];
                }
            }
            next($result);
        }
        return join(',', $result);
    }

    /**
     * 根据分类ID获取其全部父分类数据(自下向上的获取数据)
     * @param  int   $catId  分类ID
     * @return array $result array(array(父分类1_ID => 父分类2_NAME),....array(子分类ID => 子分类NAME))
     */
    public static function catRecursion($catId) {
        $result = array();
        $catInfo = self::find()->where('id = :catId', [':catId' => $catId])->one();
        while (true) {
            if ($catInfo) {
                array_unshift($result, array('id' => $catInfo['id'], 'name' => $catInfo['name']));
                $catInfo = self::find()->where('id = :catId', [':catId' => $catInfo['parent_id']])->one();
            } else {
                break;
            }
        }
        return $result;
    }

    /**
     * 对应表名
     * @return string
     */
//    public static function tableName() {
//        return '{{%category}}';
//    }
}
