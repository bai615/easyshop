<?php

namespace frontend\logics;

use common\models\Order;
use common\models\OrderGoods;
use common\utils\CommonTools;
/**
 * 订单逻辑
 *
 * @author baihua <baihua_2011@163.com>
 */
class OrderLogic {

    /**
     * 产生订单编号
     * @return string 订单ID
     */
    public static function createOrderNo() {
        return date('YmdHis') . rand(100000, 999999);
    }

    /**
     * 把订单商品同步到order_goods表中
     * @param $orderId 订单ID
     * @param $goodsInfo 商品和货品信息（购物车数据结构,countSum 最终生成的格式）
     */
    public function insertOrderGoods($orderId, $goodsInfo) {
        $orderGoodsObj = new OrderGoods();
        //清理旧的关联数据
        $orderGoodsObj->deleteAll(['order_id' => $orderId]);
        if ($goodsInfo) {
            foreach ($goodsInfo as $value) {
                //拼接商品名称和规格数据
                $specArray = array('name' => $value['name'], 'goodsno' => $value['goods_no'], 'value' => '');

                if (isset($value['spec_array'])) {
                    $spec = CommonTools::show_spec($value['spec_array']);
                    foreach ($spec as $skey => $svalue) {
                        $specArray['value'] .= $skey . ':' . $svalue . ',';
                    }
                    $specArray['value'] = trim($specArray['value'], ',');
                }
                $orderGoodsObj = new OrderGoods();
                $orderGoodsObj->order_id = $orderId;
                $orderGoodsObj->product_id = $value['product_id'];
                $orderGoodsObj->goods_id = $value['goods_id'];
                $orderGoodsObj->img = $value['img'];
                $orderGoodsObj->goods_name = $value['name'];
                $orderGoodsObj->goods_price = $value['sell_price'];
                $orderGoodsObj->real_price = $value['sell_price'];
                $orderGoodsObj->goods_nums = $value['count'];
                $orderGoodsObj->goods_weight = $value['weight'];
                $orderGoodsObj->goods_array = json_encode($specArray);
                $orderGoodsObj->save();
                $orderGoodsId = $orderGoodsObj->id;
                //下单扣库存
            }
        }
    }

    /**
     * 检查订单是否重复
     * @param array $checkData 检查的订单数据
     * @param array $goodsList 购买的商品数据信息
     */
    public static function checkRepeat($checkData, $goodsList) {
        $checkWhere = array();
        foreach ($checkData as $key => $val) {
            if (empty($val)) {
                return "请仔细填写订单所需内容";
            }
            $checkWhere[] = "`" . $key . "` = '" . $val . "'";
        }
        $checkWhere[] = " NOW() < date_add(create_time,INTERVAL 2 MINUTE) "; //在有限时间段内生成的订单
        $checkWhere[] = " pay_status != 1 "; //是否付款
        $where = join(" and ", $checkWhere);
        //查询订单数据库
        $orderObj = new Order();
        $orderList = $orderObj->find()
            ->where($where)
            ->all();
        return self::checkOrder($orderList, $goodsList);
    }

    private static function checkOrder($orderList, $goodsList) {
        //有重复下单的嫌疑
        if ($orderList) {
            //当前购买的
            $nowBuy = "";
            foreach ($goodsList as $key => $val) {
                $nowBuy .= $val['goods_id'] . "@" . $val['product_id'];
            }

            //已经购买的
            $orderGoodsObj = new OrderGoods();
            foreach ($orderList as $key => $val) {
                $isBuyed = "";
                $orderGoodsList = $orderGoodsObj->find()->where("order_id = " . $val['id'])->all();
                foreach ($orderGoodsList as $k => $item) {
                    $isBuyed .= $item['goods_id'] . "@" . $item['product_id'];
                }

                if ($nowBuy == $isBuyed) {
                    return "您所提交的订单重复，请稍候再试...";
                }
            }
        }
        return true;
    }

    /**
     * 支付成功后修改订单状态
     * @param $orderNo  string 订单编号
     * @param $adminId int    管理员ID
     * @param $note     string 收款的备注
     * @return false or int order_id
     */
    public static function updateOrderStatus($orderNo, $adminId = '', $note = '') {
        //获取订单信息
        $orderObj = new Order();
        $orderInfo = $orderObj->find(array(
            'condition' => 'order_no=:orderNo',
            'params' => array('orderNo' => $orderNo)
        ));
        //订单不存在
        if (empty($orderInfo)) {
            return false;
        }
        if ($orderInfo['pay_status'] == 1) {
            //已支付
            return $orderInfo['id'];
        } else if ($orderInfo['pay_status'] == 0) {
            //未支付
            $orderInfo->status = ($orderInfo['status'] == 5) ? 5 : 2;
            $orderInfo->pay_time = date('Y-m-d H:i:s');
            $orderInfo->pay_status = 1;
            $is_success = $orderInfo->update();
            if (!$is_success) {
                return false;
            }
            //插入收款单
            $collectionDocObj = new CollectionDoc();
            $collectionDocObj->order_id = $orderInfo['id'];
            $collectionDocObj->user_id = $orderInfo['user_id'];
            $collectionDocObj->amount = $orderInfo['order_amount'];
            $collectionDocObj->time = date('Y-m-d H:i:s');
            $collectionDocObj->payment_id = $orderInfo['pay_type'];
            $collectionDocObj->pay_status = 1;
            $collectionDocObj->note = $note;
            $collectionDocObj->admin_id = $adminId ? $adminId : 0;
            $collectionDocObj->save();

            //减少库存量
            $orderGoodsObj = new OrderGoods();
            $orderGoodsList = $orderGoodsObj->findAll(array(
                'select' => 'id',
                'condition' => 'order_id=:orderId',
                'params' => array(':orderId' => $orderInfo['id'])
            ));
            $orderGoodsListId = array();
            foreach ($orderGoodsList as $info) {
                $orderGoodsListId[] = $info['id'];
            }
            self::updateStore($orderGoodsListId, 'reduce');
            return $orderInfo['id'];
        } else {
            return false;
        }
    }

    /**
     * 订单商品数量更新操作[公共]
     * @param array $orderGoodsId ID数据
     * @param string $type 增加或者减少 add 或者 reduce
     */
    public static function updateStore($orderGoodsId, $type = 'add') {
        if (!is_array($orderGoodsId)) {
            $orderGoodsId = array($orderGoodsId);
        }
        $updateGoodsId = array();
        $orderGoodsObj = new OrderGoods();
        $goodsObj = new Goods();
        $productObj = new Products();
        $goodsList = $orderGoodsObj->findAll(array(
            'select' => array('goods_id', 'product_id', 'goods_nums'),
            'condition' => 'id in (' . join(",", $orderGoodsId) . ') and is_send = 0',
        ));
        foreach ($goodsList as $key => $val) {
            //货品库存更新
            if ($val['product_id'] != 0) {
                $productsInfo = $productObj->find(array(
                    'select' => 'store_nums',
                    'condition' => 'id=:productId',
                    'params' => array(':productId' => $val['product_id'])
                ));
                if (empty($productsInfo)) {
                    continue;
                }
                $localStoreNums = $productsInfo['store_nums'];

                //同步更新所属商品的库存量
                if (in_array($val['goods_id'], $updateGoodsId) == false) {
                    $updateGoodsId[] = $val['goods_id'];
                }

                $newStoreNums = ($type == 'add') ? $localStoreNums + $val['goods_nums'] : $localStoreNums - $val['goods_nums'];
                $newStoreNums = $newStoreNums > 0 ? $newStoreNums : 0;

                $productsInfo->store_nums = $newStoreNums;
                $productsInfo->update();
            }
            //商品库存更新
            else {
                $goodsInfo = $goodsObj->find(array(
                    'select' => 'store_nums',
                    'condition' => 'id=:goodsId',
                    'params' => array(':goodsId' => $val['goods_id'])
                ));
                if (!$goodsInfo) {
                    continue;
                }
                $localStoreNums = $goodsInfo['store_nums'];

                $newStoreNums = ($type == 'add') ? $localStoreNums + $val['goods_nums'] : $localStoreNums - $val['goods_nums'];
                $newStoreNums = $newStoreNums > 0 ? $newStoreNums : 0;

                $goodsInfo->store_nums = $newStoreNums;
                $goodsInfo->update();
            }
            //库存减少销售量增加，两者成反比
            $saleData = ($type == 'add') ? -$val['goods_nums'] : $val['goods_nums'];
            //更新goods商品销售量sale字段
            $goodsObj->updateCounters(array('sale' => $saleData), 'id=:goodsId', array(':goodsId' => $val['goods_id']));
            //更新统计goods的库存
            if ($updateGoodsId) {
                foreach ($updateGoodsId as $val) {
                    $totalRow = $productObj->getObj('goods_id = ' . $val, 'SUM(store_nums) as store');
                    $goodsObj->setData(array('store_nums' => $totalRow['store']));
                    $goodsObj->update('id = ' . $val);
                }
            }
        }
    }

}
