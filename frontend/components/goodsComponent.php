<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\components;

use yii\base\Component;
use common\models\Category;
use common\models\Goods;

/**
 * Description of goods
 *
 * @author baihua <baihua_2011@163.com>
 */
class goodsComponent extends Component {

    /**
     * 所有一级分类
     * @return type
     */
    public function getCategoryListTop() {
        $categoryModel = new Category();
        $categoryList = $categoryModel->find()
            ->select(array('id', 'name'))
            ->where('parent_id = 0 and visibility = 1')
            ->orderBy('sort asc')
            ->limit(20)
            ->all();
        return $categoryList;
    }

    /**
     * 根据一级分类输出二级分类列表
     * @param type $parentId
     * @return type
     */
    public function getCategoryByParentid($parentId) {
        $categoryModel = new Category();
        $categoryList = $categoryModel->find()
            ->select(array('id', 'name'))
            ->where('parent_id = :parentId and visibility = 1', [':parentId' => $parentId])
            ->orderBy('sort asc')
            ->limit(10)
            ->all();
        return $categoryList;
    }

    /**
     * 根据分类取销量排名列表
     * @param type $categroyId
     * @param type $limit
     * @return type
     */
    public function getCategoryExtendList($categroyId, $limit = 20) {
        $query = new \yii\db\Query;
        $query->select(array('go.id', 'go.name', 'go.img', 'go.sell_price', 'go.market_price'))
            ->distinct()
            ->from('{{%goods}} as go')
            ->leftJoin('{{%category_extend}} as ca','ca.goods_id = go.id')
            ->where('ca.category_id in (' . $categroyId . ') and go.is_del = 0')
            ->orderBy('sale desc')
            ->limit($limit);
        $command = $query->createCommand();
        return $command ->queryAll();
        
        
//        $goodsModel = new Goods();
//        $goodsInfo = $goodsModel->findAll(array(
//            'select' => array('go.id', 'go.name', 'go.img', 'go.sell_price', 'go.market_price'),
//            'condition' => 'ca.category_id in (' . $categroyId . ') and go.is_del = 0',
//            'alias' => 'go',
//            'join' => 'left join {{category_extend}} as ca on ca.goods_id = go.id',
//            'order' => 'sale desc',
//            'limit' => $limit,
//            'distinct' => true
//        ));
//        return $goodsInfo;
    }

}