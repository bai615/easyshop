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
            ->where('g.goods_id=:goodsId', [':goodsId' => $goodsId]);
        $command = $query->createCommand();
        return $command->queryAll();
    }

    /**
     * 格式化商品图片数据
     * @param type $goodsPhotoList
     * @param type $goodsPhoto
     * @return type
     */
    public static function formatGoodsPhotoList($goodsPhotoList, $goodsPhoto) {
        $goodsPhotoArr = array();
        foreach ($goodsPhotoList as $key => $value) {
            $goodsPhotoArr[$key]['img'] = $value['img'];
            $goodsPhotoArr[$key]['photo_id'] = $value['photo_id'];
            //对默认第一张图片位置进行前置
            if ($value['img'] == $goodsPhoto) {
                $temp = $goodsPhotoArr[0];
                $goodsPhotoArr[0]['img'] = $value['img'];
                $goodsPhotoArr[0]['photo_id'] = $value['photo_id'];
                $goodsPhotoArr[$key] = $temp;
            }
        }
        return $goodsPhotoArr;
    }

}
