<?php

namespace frontend\models;

use common\models\Goods;
use common\models\Products;
use frontend\logics\CartLogic;

/**
 * 计算购物车中的商品价格
 *
 * @author baihua <baihua_2011@163.com>
 */
class CountSum {

    /**
     * 错误信息
     * @var type 
     */
    private $error = '';

    //购物车计算
    public function cartCount($id = '', $type = '', $buyNum = 1) {
        //单品购买
        if ($id && $type) {
            $type = ($type == "goods") ? "goods" : "product";

            //规格必填
            if ($type == "goods") {
                $productsModel = new Products();
                $productsInfo = $productsModel->find()->where('goods_id=:goodsId', [':goodsId' => $id])->one();
                if ($productsInfo) {
                    $this->error = '请先选择商品的规格';
                    return false;
                }
            }

            $buyInfo = array(
                $type => array('id' => array($id), 'data' => array($id => array('count' => $buyNum)), 'count' => $buyNum)
            );
        } else {
            //获取购物车中的商品和货品信息
            $cartLogic = new CartLogic();
            $buyInfo = $cartLogic->getMyCart();
        }
        return $this->goodsCount($buyInfo);
    }

    /**
	 * 计算商品价格
	 * @param Array $buyInfo ,购物车格式
	 * @return array or bool
	 */
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

        /* Product 拼装商品数据 */
        if (isset($buyInfo['product']['id']) && $buyInfo['product']['id']) {
            //购物车中的货品数据
            $productList = Products::findOne($buyInfo['product']['id']);
            $productGoods = $productList->goods;
            //检查库存
            if ($buyInfo['product']['data'][$productList['id']]['count'] <= 0 || $buyInfo['product']['data'][$productList['id']]['count'] > $productGoods['store_nums']) {
//                    $productList[$key]['name'] .= "【无库存】";
//                    $this->error .= "<货品：" . $val['name'] . "> 购买数量超出库存，请重新调整购买数量。";
            }

            $key = 0;
            $newProductList[$key]['goods_id'] = $productGoods['id'];
            $newProductList[$key]['product_id'] = $productList['id'];
            $newProductList[$key]['goods_no'] = $productGoods['goods_no'];
            $newProductList[$key]['name'] = $productGoods['name'];
            $newProductList[$key]['cost_price'] = $productGoods['cost_price'];
            $newProductList[$key]['img'] = $productGoods['img'];
            $newProductList[$key]['sell_price'] = $productGoods['sell_price'];
            $newProductList[$key]['weight'] = $productGoods['weight'];
            $newProductList[$key]['store_nums'] = $productGoods['store_nums'];
            $newProductList[$key]['spec_array'] = $productList['spec_array'];

            $newProductList[$key]['count'] = $buyInfo['product']['data'][$productList['id']]['count'];
            $current_sum_all = $newProductList[$key]['sell_price'] * $newProductList[$key]['count'];
            $newProductList[$key]['sum'] = $current_sum_all;

            //全局统计
            $this->weight += $productList['weight'] * $newProductList[$key]['count'];
            $this->sum += $current_sum_all;
            $this->count += $newProductList[$key]['count'];
        }
        $this->final_sum = $this->sum;
        $resultList = array_merge($newGoodsList, $newProductList);
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
