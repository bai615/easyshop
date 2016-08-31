<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * 货品表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Products extends ActiveRecord {

    public $maxSellPrice; //最高售价
    public $minSellPrice; //最低售价
    public $maxMarketPrice; //最高市场价
    public $minMarketPrice; //最低市场价

    /**
     * 获取产品数据
     * @param type $goodsId
     * @return type
     */

    public function getProductList($goodsId) {
        $priceArea = array();
        //查询产品数据
        $productList = $this->find()
            ->select(['max(sell_price) as maxSellPrice', 'min(sell_price) as minSellPrice', 'max(market_price) as maxMarketPrice', 'min(market_price) as minMarketPrice'])
            ->where('goods_id=:goodsId', [':goodsId' => $goodsId])
            ->one();
        if ($productList) {
            //整理返回数据
            $priceArea['maxSellPrice'] = $productList['maxSellPrice'];
            $priceArea['minSellPrice'] = $productList['minSellPrice'];
            $priceArea['minMarketPrice'] = $productList['minMarketPrice'];
            $priceArea['maxMarketPrice'] = $productList['maxMarketPrice'];
        }
        return $priceArea;
    }

}
