<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * 相册商品关系表
 *
 * @author baihua <baihua_2011@163.com>
 */
class GoodsPhotoRelation extends ActiveRecord {

    /**
     * 获取商品图片
     * @param type $goodsId
     * @return type
     */
    public static function getGoodsPhotoList($goodsId) {
        $query = new \yii\db\Query;
        $query->select(['p.id as photo_id', 'p.img'])
            ->distinct()
            ->from('{{%goods_photo_relation}} as g')
            ->leftJoin('{{%goods_photo}} as p', 'p.id=g.photo_id')
            ->where('g.goods_id=:goodsId',[':goodsId' => $goodsId]);
        $command = $query->createCommand();
        return $command->queryAll();
    }

}
