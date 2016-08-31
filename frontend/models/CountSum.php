<?php

namespace frontend\models;

use common\models\Goods;
use common\models\Products;

/**
 * 计算购物车中的商品价格
 *
 * @author baihua <baihua_2011@163.com>
 */
class CountSum {

    //购物车计算
    public function cartCount($id = '', $type = '', $buyNum = 1) {
        //单品购买
        if ($id && $type) {
            $type = ($type == "goods") ? "goods" : "product";

            //规格必填
            if ($type == "goods") {
//                $productsDB = new IModel('products');
//                if ($productsDB->getObj('goods_id = ' . $id)) {
//                    $this->error .= '请先选择商品的规格';
//                    return $this->error;
//                }
            }

            $buyInfo = array(
                $type => array('id' => array($id), 'data' => array($id => array('count' => $buyNum)), 'count' => $buyNum)
            );
//            pprint($buyInfo);
        } else {
            //获取购物车中的商品和货品信息
//            $cartObj = new Cart();
//            $buyInfo = $cartObj->getMyCart();
        }
        return $this->goodsCount($buyInfo);
    }

    public function goodsCount($buyInfo) {
        $this->sum = 0;       //原始总额(优惠前)
        $this->final_sum = 0;       //应付总额(优惠后)
        $this->weight = 0;       //总重量
        $this->count = 0;       //总数量

        $newGoodsList = array();
        $newProductList = array();

        //获取商品或货品数据
        /* Goods 拼装商品数据 */
        if (isset($buyInfo['goods']['id']) && $buyInfo['goods']['id']) {
            //购物车中的商品数据
            $goodsIdStr = join(',', $buyInfo['goods']['id']);
            $goodsObj = new Goods();
            $goodsList = $goodsObj->find()
                ->select(['id as goods_id', 'name', 'cost_price', 'img', 'sell_price', 'weight', 'store_nums', 'goods_no'])
                ->where('id in (' . $goodsIdStr . ')')
                ->all();

            $newGoodsList = array();
            //开始优惠情况判断
            foreach ($goodsList as $key => $val) {
                //检查库存
                if ($buyInfo['goods']['data'][$val['goods_id']]['count'] <= 0 || $buyInfo['goods']['data'][$val['goods_id']]['count'] > $val['store_nums']) {
//                    $goodsList[$key]['name'] .= "【无库存】";
//                    $this->error .= "<商品：" . $val['name'] . "> 购买数量超出库存，请重新调整购买数量。";
                }

                $newGoodsList[$key]['goods_id'] = $val['goods_id'];
                $newGoodsList[$key]['product_id'] = 0;
                $newGoodsList[$key]['goods_no'] = $val['goods_no'];
                $newGoodsList[$key]['name'] = $val['name'];
                $newGoodsList[$key]['cost_price'] = $val['cost_price'];
                $newGoodsList[$key]['img'] = $val['img'];
                $newGoodsList[$key]['sell_price'] = $val['sell_price'];
                $newGoodsList[$key]['weight'] = $val['weight'];
                $newGoodsList[$key]['store_nums'] = $val['store_nums'];
                $newGoodsList[$key]['spec_array'] = '';

                $newGoodsList[$key]['count'] = $buyInfo['goods']['data'][$val['goods_id']]['count'];
                $current_sum_all = $goodsList[$key]['sell_price'] * $newGoodsList[$key]['count'];
                $newGoodsList[$key]['sum'] = $current_sum_all;

                //全局统计
                $this->weight += $val['weight'] * $newGoodsList[$key]['count'];
                $this->sum += $current_sum_all;
                $this->count += $newGoodsList[$key]['count'];
            }
        }
//        pprint($newGoodsList);

        /* Product 拼装商品数据 */
        if (isset($buyInfo['product']['id']) && $buyInfo['product']['id']) {
            //购物车中的货品数据
            $product = Products::findOne($buyInfo['product']['id']);
            $productList = $product->goods;
            dprint($productList);
//            $productIdStr = join(',', $buyInfo['product']['id']);
            /*
            $productObj = new Products();
            $criteria = new CDbCriteria;
            $criteria->addInCondition('pro.id', $buyInfo['product']['id']); //代表where id IN (1,2,3,4,5,);  
            $criteria->with = 'r_goods';
            $criteria->alias = 'pro';
            $productList = $productObj->findAll($criteria);
             */
//            pprint($productList);
            //开始优惠情况判断
            foreach ($productList as $key => $val) {
                //检查库存
                if ($buyInfo['product']['data'][$val['id']]['count'] <= 0 || $buyInfo['product']['data'][$val['id']]['count'] > $val['r_goods']['store_nums']) {
//                    $productList[$key]['name'] .= "【无库存】";
//                    $this->error .= "<货品：" . $val['name'] . "> 购买数量超出库存，请重新调整购买数量。";
                }


                $newProductList[$key]['goods_id'] = $val['r_goods']['id'];
                $newProductList[$key]['product_id'] = $val['id'];
                $newProductList[$key]['goods_no'] = $val['r_goods']['goods_no'];
                $newProductList[$key]['name'] = $val['r_goods']['name'];
                $newProductList[$key]['cost_price'] = $val['r_goods']['cost_price'];
                $newProductList[$key]['img'] = $val['r_goods']['img'];
                $newProductList[$key]['sell_price'] = $val['r_goods']['sell_price'];
                $newProductList[$key]['weight'] = $val['r_goods']['weight'];
                $newProductList[$key]['store_nums'] = $val['r_goods']['store_nums'];
                $newProductList[$key]['spec_array'] = $val['spec_array'];

                $newProductList[$key]['count'] = $buyInfo['product']['data'][$val['id']]['count'];
                $current_sum_all = $newProductList[$key]['sell_price'] * $newProductList[$key]['count'];
                $newProductList[$key]['sum'] = $current_sum_all;

                //全局统计
                $this->weight += $val['weight'] * $newProductList[$key]['count'];
                $this->sum += $current_sum_all;
                $this->count += $newProductList[$key]['count'];
            }
        }
//        pprint($newProductList);
        $this->final_sum = $this->sum;
        $resultList = array_merge($newGoodsList, $newProductList);
//        pprint($resultList);
        if (!$resultList) {
//            $this->error .= "商品信息不存在";
        }

        return array(
            'final_sum' => $this->final_sum,
            'sum' => $this->sum,
            'goodsList' => $resultList,
            'count' => $this->count,
            'weight' => $this->weight,
        );
    }

}
