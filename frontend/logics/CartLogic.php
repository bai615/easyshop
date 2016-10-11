<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\logics;

use Yii;
use yii\helpers\Json;
use common\models\Goods;
use common\models\Products;
use frontend\models\Cart;
use backend\utils\Thumb;

/**
 * Description of CartLogic
 *
 * @author baihua <baihua_2011@163.com>
 */
class CartLogic {

    //购物车中最多容纳的数量
    private $maxCount = 100;

    /* 购物车简单cookie存储结构
     * array [goods]=>array(商品主键=>数量) , [product]=>array( 货品主键=>数量 )
     */
    private $cartStruct = ['goods' => [], 'product' => []];

    /* 购物车复杂存储结构
     * [id]   :array  商品id值;
     * [count]:int    商品数量;
     * [info] :array  商品信息 [goods]=>array( ['id']=>商品ID , ['data'] => array( [商品ID]=>array ( [sell_price]价格, [count]购物车中此商品的数量 ,[type]类型goods,product ,[goods_id]商品ID值 ) ) ) , [product]=>array( 同上 ) , [count]购物车商品和货品数量 , [sum]商品和货品总额 ;
     * [sum]  :int    商品总价格;
     */
    private $cartExeStruct = array('goods' => array('id' => array(), 'data' => array()), 'product' => array('id' => array(), 'data' => array()), 'count' => 0, 'sum' => 0);
    //错误信息
    private $error = '';

    /**
     * 将商品或者货品加入购物车
     * @param $gid  商品或者货品ID值
     * @param $num  购买数量
     * @param $type 加入类型 goods商品; product:货品;
     */
    public function add($gid, $num = 1, $type = 'goods') {
        //规格必填
        if ($type == "goods") {
            $productsModel = new Products();
            $productsInfo = $productsModel->find()->where('goods_id=:goodsId', [':goodsId' => $gid])->one();
            if ($productsInfo) {
                $this->error = '请先选择商品的规格';
                return false;
            }
        }
        //购物车中已经存在此商品
        $cartInfo = $this->getMyCartStruct();

        if ($this->getCartSort($cartInfo) >= $this->maxCount) {
            $this->error = '加入购物车失败,购物车中最多只能容纳' . $this->maxCount . '种商品';
            return false;
        } else {
            $cartInfo = $this->getUpdateCartData($cartInfo, $gid, $num, $type);
            if ($cartInfo === false) {
                return false;
            } else {
                return $this->setMyCart($cartInfo);
            }
        }
    }

    /**
     * 获取当前购物车简单信息
     * @return 获取cartStruct数据结构
     */
    private function getMyCartStruct() {
        $cartResult = [];
        $userId = Yii::$app->controller->data['shopUserInfo']['userId'];
        if ($userId) {
            $cartModel = new Cart();
            $cartInfo = $cartModel->find()->where('user_id = :userId', [':userId' => $userId])->one();
            //db存在购物车
            if ($cartInfo && $cartInfo['content']) {
                $cartResult = $this->decode($cartInfo['content']);
            }
        }
        return $cartResult ? $cartResult : $this->cartStruct;
    }

    /**
     * 计算商品的种类
     * @param type $mycart
     * @return type
     */
    private function getCartSort($mycart) {
        $sumSort = 0;
        $sortArray = array('goods', 'product');
        foreach ($sortArray as $sort) {
            if (isset($mycart[$sort])) {
                $sumSort += count($mycart[$sort]);
            }
        }
        return $sumSort;
    }

    /**
     * 获取新加入购物车的数据
     * @param $cartInfo cartStruct
     * @param $gid 商品或者货品ID
     * @param $num 数量
     * @param $type goods 或者 product
     */
    private function getUpdateCartData($cartInfo, $gid, $num, $type) {
        $gid = intval($gid);
        $num = intval($num);
        if ($type != 'goods') {
            $type = 'product';
        }

        //获取基本的商品数据
        $goodsRow = $this->getGoodInfo($gid, $type);
        if ($goodsRow) {
            //购物车中已经存在此类商品
            if (isset($cartInfo[$type][$gid])) {
                if ($goodsRow['store_nums'] < $cartInfo[$type][$gid] + $num) {
                    $this->error = '该商品库存不足';
                    return false;
                }
                $cartInfo[$type][$gid] += $num;
            }

            //购物车中不存在此类商品
            else {
                if ($goodsRow['store_nums'] < $num) {
                    $this->error = '该商品库存不足';
                    return false;
                }
                $cartInfo[$type][$gid] = $num;
            }

            return $cartInfo;
        } else {
            $this->error = '该商品库存不足';
            return false;
        }
    }

    /**
     * 根据 $gid 获取商品信息
     * @param type $gid
     * @param type $type
     * @return type
     */
    private function getGoodInfo($gid, $type = 'goods') {
        $dataArray = array();
        //商品方式
        if ($type == 'goods') {
            $query = new \yii\db\Query;
            $query->select(['id', 'sell_price', 'store_nums'])
                ->distinct()
                ->from('{{%goods}}')
                ->where('id=:goodsId and is_del=0', [':goodsId' => $gid]);
            $command = $query->createCommand();
            $dataArray = $command->queryOne();
        }
        //货品方式
        else {
            $query = new \yii\db\Query;
            $query->select(['go.id as goods_id', 'pro.sell_price', 'pro.store_nums', 'pro.id'])
                ->distinct()
                ->from('{{%products}} as pro')
                ->leftJoin('{{%goods}} as go', 'pro.goods_id = go.id')
                ->where('pro.id = :productId and go.is_del = 0', [':productId' => $gid]);
            $command = $query->createCommand();
            $dataArray = $command->queryOne();
        }
        return $dataArray;
    }

    /**
     * 写入购物车
     * @param type $goodsInfo
     * @return type
     */
    private function setMyCart($goodsInfo) {
        $goodsInfo = $this->encode($goodsInfo);
        $result = false;
        $userId = Yii::$app->controller->data['shopUserInfo']['userId'];
        //用户存在写入db
        if ($userId) {
            $cartModel = new Cart();
            $cartInfo = $cartModel->find()->where('user_id = :userId', [':userId' => $userId])->one();
            if ($cartInfo) {
                //更新购物车信息
                $cartInfo->content = $goodsInfo;
                $result = $cartInfo->update();
            } else {
                $cartModel->content = $goodsInfo;
                $cartModel->user_id = $userId;
                $cartModel->create_time = date('Y-m-d H:i:s', time());
                $result = $cartModel->save();
            }
        }else{
            $this->error = '请先登录';
        }
        return $result;
    }

    //购物车存储数据编码
    private function encode($data) {
        return str_replace(array('"', ','), array('&', '$'), Json::encode($data));
    }

    //购物车存储数据解码
    private function decode($data) {
        return Json::decode(str_replace(array('&', '$'), array('"', ','), $data));
    }

    //获取错误信息
    public function getError() {
        return $this->error;
    }

    /**
     * 获取当前购物车完整信息
     * @return 获取cartExeStruct数据结构
     */
    public function getMyCart() {
        $cartValue = $this->getMyCartStruct();
        return $this->cartFormat($cartValue);
    }

    /**
     * 把cookie的结构转化成为程序所用的数据结构
     * @param  $cartValue 购物车cookie存储结构
     * @return array : [goods]=>array( ['id']=>商品ID , ['data'] => array( [商品ID]=>array ([name]商品名称 , [img]图片地址 , [sell_price]价格, [count]购物车中此商品的数量 ,[type]类型goods,product , [goods_id]商品ID值 ) ) ) , [product]=>array( 同上 ) , [count]购物车商品和货品数量 , [sum]商品和货品总额 ;
     */
    public function cartFormat($cartValue) {
        //初始化结果
        $result = $this->cartExeStruct;

        $goodsIdArray = array();

        if (isset($cartValue['goods']) && $cartValue['goods']) {
            $goodsIdArray = array_keys($cartValue['goods']);
            $result['goods']['id'] = $goodsIdArray;
            foreach ($goodsIdArray as $gid) {
                $result['goods']['data'][$gid] = array(
                    'id' => $gid,
                    'type' => 'goods',
                    'goods_id' => $gid,
                    'count' => $cartValue['goods'][$gid],
                );

                //购物车中的种类数量累加
                $result['count'] += $cartValue['goods'][$gid];
            }
        }

        if (isset($cartValue['product']) && $cartValue['product']) {
            $productIdArray = array_keys($cartValue['product']);
            $result['product']['id'] = $productIdArray;

            $productModel = new Products();
            $productData = $productModel->find()->select(['id', 'goods_id', 'sell_price'])->where('id in (' . join(",", $productIdArray) . ')')->all();
            foreach ($productData as $proVal) {
                $result['product']['data'][$proVal['id']] = array(
                    'id' => $proVal['id'],
                    'type' => 'product',
                    'goods_id' => $proVal['goods_id'],
                    'count' => $cartValue['product'][$proVal['id']],
                    'sell_price' => $proVal['sell_price'],
                );

                if (!in_array($proVal['goods_id'], $goodsIdArray)) {
                    $goodsIdArray[] = $proVal['goods_id'];
                }

                //购物车中的种类数量累加
                $result['count'] += $cartValue['product'][$proVal['id']];
            }
        }

        if ($goodsIdArray) {
            $goodsArray = array();

            $goodsModel = new Goods();
            $goodsData = $goodsModel->find()->select(['id', 'name', 'img', 'sell_price'])->where('id in (' . join(",", $goodsIdArray) . ')')->all();
            foreach ($goodsData as $goodsVal) {
                $goodsArray[$goodsVal['id']] = $goodsVal;
            }
            $thumb = new Thumb(Yii::$app->params['upload_path']);
            foreach ($result['goods']['data'] as $key => $val) {
                if (isset($goodsArray[$val['goods_id']])) {
                    $result['goods']['data'][$key]['img'] = $thumb->get($goodsArray[$val['goods_id']]['img'], 120, 120);
                    $result['goods']['data'][$key]['name'] = $goodsArray[$val['goods_id']]['name'];
                    $result['goods']['data'][$key]['sell_price'] = $goodsArray[$val['goods_id']]['sell_price'];

                    //购物车中的金额累加
                    $result['sum'] += $goodsArray[$val['goods_id']]['sell_price'] * $val['count'];
                }
            }

            foreach ($result['product']['data'] as $key => $val) {
                if (isset($goodsArray[$val['goods_id']])) {
                    $result['product']['data'][$key]['img'] = $thumb->get($goodsArray[$val['goods_id']]['img'], 120, 120);
                    $result['product']['data'][$key]['name'] = $goodsArray[$val['goods_id']]['name'];

                    //购物车中的金额累加
                    $result['sum'] += $result['product']['data'][$key]['sell_price'] * $val['count'];
                }
            }
        }
        return $result;
    }

}
