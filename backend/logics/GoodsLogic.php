<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\logics;

use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use common\models\Goods;
use common\models\GoodsAttribute;
use common\models\CategoryExtend;
use common\models\CommendGoods;
use common\models\GoodsPhotoRelation;
use common\models\GoodsPhoto;
use common\models\Products;

/**
 * Description of GoodsLogic
 *
 * @author baihua <baihua_2011@163.com>
 */
class GoodsLogic {

    public function update($goodsId, $paramData) {
        $postData = array();
        $nowDataTime = date('Y-m-d H:i:s', time());
        foreach ($paramData as $key => $val) {
            $postData[$key] = $val;

            //数据过滤分组
            if (strpos($key, 'attr_id_') !== false) {
                $goodsAttrData[ltrim($key, 'attr_id_')] = ($val);
            } else if ($key == 'content') {
                $goodsUpdateData['content'] = ($val);
            } else if ($key[0] != '_') {
                $goodsUpdateData[$key] = ($val);
            }
        }

        //上架或者下架处理
        if (isset($goodsUpdateData['is_del'])) {
            //上架
            if ($goodsUpdateData['is_del'] == 0) {
                $goodsUpdateData['up_time'] = $nowDataTime;
                $goodsUpdateData['down_time'] = null;
            }
            //下架
            else if ($goodsUpdateData['is_del'] == 2) {
                $goodsUpdateData['up_time'] = null;
                $goodsUpdateData['down_time'] = $nowDataTime;
            }
            //审核或者删除
            else {
                $goodsUpdateData['up_time'] = null;
                $goodsUpdateData['down_time'] = null;
            }
        }

        //是否存在货品
        $goodsUpdateData['spec_array'] = '';
        if (isset($postData['_spec_array'])) {
            //生成goods中的spec_array字段数据
            $goods_spec_array = array();
            foreach ($postData['_spec_array'] as $key => $val) {
                foreach ($val as $v) {
                    $tempSpec = Json::decode($v, true);
                    if (!isset($goods_spec_array[$tempSpec['id']])) {
                        $goods_spec_array[$tempSpec['id']] = array('id' => $tempSpec['id'], 'name' => $tempSpec['name'], 'type' => $tempSpec['type'], 'value' => array());
                    }
                    $goods_spec_array[$tempSpec['id']]['value'][] = $tempSpec['value'];
                }
            }
            foreach ($goods_spec_array as $key => $val) {
                $val['value'] = array_unique($val['value']);
                $goods_spec_array[$key]['value'] = join(',', $val['value']);
            }
            $goodsUpdateData['spec_array'] = Json::encode($goods_spec_array);
        }

        $goodsUpdateData['goods_no'] = isset($postData['_goods_no']) ? current($postData['_goods_no']) : '';
        $goodsUpdateData['store_nums'] = array_sum($postData['_store_nums']);
        $goodsUpdateData['market_price'] = isset($postData['_market_price']) ? current($postData['_market_price']) : 0;
        $goodsUpdateData['sell_price'] = isset($postData['_sell_price']) ? current($postData['_sell_price']) : 0;
        $goodsUpdateData['cost_price'] = isset($postData['_cost_price']) ? current($postData['_cost_price']) : 0;
        $goodsUpdateData['weight'] = isset($postData['_weight']) ? current($postData['_weight']) : 0;

        //处理商品
        $goodsModel = new Goods();
        if ($goodsId) {

            $result = $goodsModel->updateAll($goodsUpdateData, 'id=:goodsId', [':goodsId' => $goodsId]);

            if (empty($result)) {
                die("更新商品错误");
            }
        } else {
            foreach ($goodsUpdateData as $key => $value) {
                $goodsModel->$key = $value;
            }
            $goodsModel->create_time = $nowDataTime;
            $goodsModel->save();
            $goodsId = $goodsModel->id;
        }

        //处理商品属性
        $goodsAttrModel = new GoodsAttribute();
        $goodsAttrModel->deleteAll('goods_id = :goodsId', [':goodsId' => $goodsId]);
        if ($goodsUpdateData['model_id'] > 0 && isset($goodsAttrData) && $goodsAttrData) {
            foreach ($goodsAttrData as $key => $val) {
                $goodsAttrModel = new GoodsAttribute();
                $goodsAttrModel->goods_id = $goodsId;
                $goodsAttrModel->model_id = $goodsUpdateData['model_id'];
                $goodsAttrModel->attribute_id = $key;
                $goodsAttrModel->attribute_value = is_array($val) ? join(',', $val) : $val;
                $goodsAttrModel->save();
            }
        }

        //是否存在货品
        $productsModel = new Products();
        $productsModel->deleteAll('goods_id = :goodsId', [':goodsId' => $goodsId]);
        if (isset($postData['_spec_array'])) {
            $productIdArray = array();

            //创建货品信息
            foreach ($postData['_goods_no'] as $key => $rs) {
                $productsModel = new Products();
                $productsModel->goods_id = $goodsId;
                $productsModel->products_no = $postData['_goods_no'][$key];
                $productsModel->store_nums = $postData['_store_nums'][$key];
                $productsModel->market_price = $postData['_market_price'][$key];
                $productsModel->sell_price = $postData['_sell_price'][$key];
                $productsModel->cost_price = $postData['_cost_price'][$key];
                $productsModel->weight = $postData['_weight'][$key];
                $productsModel->spec_array = "[" . join(',', $postData['_spec_array'][$key]) . "]";
                $productsModel->save();
                $productIdArray[$key] = $productsModel->id;
            }
        }

        //处理商品分类
        $categoryModel = new CategoryExtend();
        $categoryModel->deleteAll('goods_id = :goodsId', [':goodsId' => $goodsId]);
        if (isset($postData['_goods_category']) && $postData['_goods_category']) {
            foreach ($postData['_goods_category'] as $item) {
                $categoryModel = new CategoryExtend();
                $categoryModel->goods_id = $goodsId;
                $categoryModel->category_id = $item;
                $categoryModel->save();
            }
        }

        //处理商品促销
        $commendModel = new CommendGoods();
        $commendModel->deleteAll('goods_id = :goodsId', [':goodsId' => $goodsId]);
        if (isset($postData['_goods_commend']) && $postData['_goods_commend']) {
            foreach ($postData['_goods_commend'] as $item) {
                $commendModel = new CommendGoods();
                $commendModel->goods_id = $goodsId;
                $commendModel->commend_id = $item;
                $commendModel->save();
            }
        }

        //处理商品图片
        $photoRelationModel = new GoodsPhotoRelation();
        $photoRelationModel->deleteAll('goods_id = :goodsId', [':goodsId' => $goodsId]);
        if (isset($postData['_imgList']) && $postData['_imgList']) {
            $postData['_imgList'] = str_replace(',', '","', trim($postData['_imgList'], ','));
            $photoModel = new GoodsPhoto();
            $photoData = $photoModel->find()->select(['id'])->where('img in ("' . $postData['_imgList'] . '")')->all();
            if ($photoData) {
                foreach ($photoData as $item) {
                    $photoRelationModel = new GoodsPhotoRelation();
                    $photoRelationModel->goods_id = $goodsId;
                    $photoRelationModel->photo_id = $item['id'];
                    $photoRelationModel->save();
                }
            }
        }

        return $goodsId;
    }

    /**
     * 获取编辑商品所有数据
     * @param int $goodsId 商品ID
     */
    public function edit($goodsId) {
        //获取商品
        $goodsModel = new Goods();
        $goodsInfo = $goodsModel->find()
            ->where('id=:goodsId', [':goodsId' => $goodsId])
            ->one();

        if (empty($goodsInfo)) {
            return null;
        }

        //赋值到FORM用于渲染
        $data = array('form_info' => ArrayHelper::toArray($goodsInfo));

        //获取货品
        $productModel = new Products();
        $productInfo = $productModel->find()
            ->where('goods_id=:goodsId', [':goodsId' => $goodsId])
            ->all();


        if ($productInfo) {
            $data['product'] = ArrayHelper::toArray($productInfo);
        }

        //加载推荐类型
        $commendGoodsModel = new CommendGoods();
        $commendGoods = $commendGoodsModel->find()
            ->select(['commend_id'])
            ->where('goods_id=:goodsId', [':goodsId' => $goodsId])
            ->all();

        if ($commendGoods) {
            foreach ($commendGoods as $value) {
                $data['goods_commend'][] = $value['commend_id'];
            }
        }

        //相册
        $query = new \yii\db\Query;
        $query->select(['gh.img'])
            ->from('{{%goods_photo_relation}} as ghr')
            ->leftJoin('{{%goods_photo}} as gh', 'ghr.photo_id=gh.id')
            ->where('ghr.goods_id=:goodsId', [':goodsId' => $goodsId])
            ->orderBy('ghr.id asc');
        $command = $query->createCommand();
        $photoInfo = $command->queryAll();
        if ($photoInfo) {
            $data['goods_photo'] = $photoInfo;
        }

        //扩展基本属性
        $goodsAttrModel = new GoodsAttribute();
        $attrInfo = $goodsAttrModel->find()
            ->where('goods_id=:goodsId and attribute_id != ""', [':goodsId' => $goodsId])
            ->all();
        if ($attrInfo) {
            foreach ($attrInfo as $item) {
                //key：属性名；val：属性值,多个属性值以","分割
                $data['goods_attr'][$item['attribute_id']] = $item['attribute_value'];
            }
        }

        //商品分类
        $query = new \yii\db\Query;
        $query->from('{{%category_extend}} as ca')
            ->leftJoin('{{%category}} as c', 'c.id=ca.category_id')
            ->where('ca.goods_id=:goodsId', [':goodsId' => $goodsId]);
        $command = $query->createCommand();
        $categoryInfo = $command->queryAll();
        if ($categoryInfo) {
            $data['goods_category'] = $categoryInfo;
        }

//        $categoryExtend = new CategoryExtend();
//        $cateData = $categoryExtend->find()
////            ->select(['category_id'])
//            ->where('goods_id=:goodsId', [':goodsId' => $goodsId])
//            ->all();
//        if ($cateData) {
//            foreach ($cateData as $item) {
//                $data['goods_category'][] = $item['category_id'];
//            }
//        }
        return $data;
    }

    /**
     * 根据商品分类的父类ID进行数据归类
     * @param array $categoryData 商品category表的结构数组
     * @return array
     */
    public static function categoryParentStruct($categoryData) {
        $result = array();
        foreach ($categoryData as $key => $val) {
            if (isset($result[$val['parent_id']]) && is_array($result[$val['parent_id']])) {
                $result[$val['parent_id']][] = $val;
            } else {
                $result[$val['parent_id']] = array($val);
            }
        }
        return $result;
    }

    /**
     * 无限极分类递归函数
     * @staticvar array $formatCat
     * @staticvar int $floor
     * @param type $catArray
     * @param type $id
     * @param type $prefix
     * @return int
     */
    public static function sortdata($catArray, $id = 0, $prefix = '') {
        static $formatCat = array();
        static $floor = 0;

        foreach ($catArray as $key => $val) {
            if ($val['parent_id'] == $id) {
                $str = self::nstr($prefix, $floor);
                $val['name'] = $str . $val['name'];

                $val['floor'] = $floor;
                $formatCat[] = $val;

                unset($catArray[$key]);

                $floor++;
                self::sortdata($catArray, $val['id'], $prefix);
                $floor--;
            }
        }
        return $formatCat;
    }

    //处理商品列表显示缩进
    public static function nstr($str, $num = 0) {
        $return = '';
        for ($i = 0; $i < $num; $i++) {
            $return .= $str;
        }
        return $return;
    }

    /**
     * 格式化商品分类数据
     * @param type $catArray
     * @param type $id
     * @return type
     */
    public static function formatData($catArray, $id = 0) {
        $formatCat = array();

        foreach ($catArray as $key => $catInfo) {

            if ($id == $catInfo['parent_id']) {
                $tmpMenu['id'] = $catInfo['id'];
                $tmpMenu['name'] = $catInfo['name'];
                unset($catInfo[$key]);
                $tmpMenu['childs'] = self::formatData($catArray, $catInfo['id']);
                $formatCat[] = $tmpMenu;
            }
        }
        return $formatCat;
    }

}
