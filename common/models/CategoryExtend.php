<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * 商品扩展分类表
 *
 * @author baihua <baihua_2011@163.com>
 */
class CategoryExtend extends ActiveRecord {

    /**
     * 获取商品分类
     * @param type $goodsId
     * @return type
     */
    public static function getCategoryId($goodsId) {
        $query = new \yii\db\Query;
        $query->select(['c.id'])
            ->from('{{%category_extend}} as ca')
            ->leftJoin('{{%category}} as c', 'c.id=ca.category_id')
            ->where('ca.goods_id = :goodsId', [':goodsId' => $goodsId])
            ->orderBy('ca.id desc');
        $command = $query->createCommand();
        return $command->queryOne();
    }

    /**
     * 获取分类下商品
     * @param type $categoryId
     * @return type
     */
    public static function getGoodsId($categoryId) {
        $result = array();
        $list = self::find()->where('category_id in (' . $categoryId . ')')->all();
        if ($list) {
            foreach ($list as $info) {
                $result[] = $info['goods_id'];
            }
        }
        return join(',', array_unique($result));
    }

}
