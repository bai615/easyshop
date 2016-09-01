<?php

namespace frontend\logics;

use common\models\Goods;
use common\models\Products;

/**
 * Description of GoodsLogic
 *
 * @author baihua <baihua_2011@163.com>
 */
class GoodsLogic {

    /**
     * 检查商品或者货品的库存是否充足
     * @param $buyNum 检查数量
     * @param $goodsId 商品id
     * @param $productId 货品id
     * @result array() true:满足数量; false:不满足数量
     */
    public static function checkStore($buyNum, $goodsId, $productId = 0) {
        $data = $productId ? self::getProductStore($productId) : self::getGoodsStore($goodsId);
        //库存判断
        if (empty($data) || $buyNum <= 0 || $buyNum > $data['store_nums']) {
            return false;
        }
        return true;
    }

    /**
     * 获取商品库存
     * @param type $goodsId
     * @return type
     */
    private static function getGoodsStore($goodsId) {
        $goodsObj = new Goods();
        return $goodsObj->find()
            ->select(['store_nums'])
            ->where('id=:goodsId and is_del = 0',[':goodsId' => $goodsId])
            ->one();
    }

    /**
     * 获取货品库存
     * @param type $productId
     * @return type
     */
    private static function getProductStore($productId) {
        $productObj = new Products();
        return $productObj->find()
            ->select(['store_nums'])
            ->where('id=:productId and is_del = 0',[':productId' => $productId])
            ->one();
    }

    //取货品数据
    private static function getProductInfo($productId) {
        pprint($productId);
        $productObj = new Products();
        $criteria = new CDbCriteria;
        $criteria->addInCondition('pro.id', array($productId)); //代表where id IN (1,2,3,4,5,);  
        $criteria->with = 'r_goods';
        $criteria->alias = 'pro';
        $productList = $productObj->findAll($criteria);
        echo '----';
        pprint($productList);
        /*
          'name'   => 'goods as go,products as pro',
          'where'  => 'pro.id = #id# and pro.goods_id = go.id and go.is_del = 0',
          'fields' => 'pro.sell_price,pro.weight,pro.id as product_id,pro.spec_array,pro.goods_id,pro.store_nums,pro.products_no as goods_no,go.name,go.point,go.exp,go.img,go.seller_id',
          'type'   => 'row',
         */
    }

}
