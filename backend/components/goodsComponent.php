<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\components;

use yii\base\Component;
use common\models\BrandCategory;

/**
 * Description of goodsComponent
 *
 * @author baihua <baihua_2011@163.com>
 */
class goodsComponent extends Component {

    /**
     * 获取分类名称
     * @param type $goodsId
     * @return string
     */
    public function getCategoryName($goodsId) {
        $query = new \yii\db\Query;
        $query->select(array('cd.name'))
            ->distinct()
            ->from('{{%category_extend}} as ce')
            ->leftJoin('{{%category}} as cd', 'cd.id = ce.category_id')
            ->where('ce.goods_id=:goodsId', array(':goodsId' => $goodsId))
            ->orderBy('cd.id asc');
        $command = $query->createCommand();
        $list = $command->queryAll();
        $resultStr = '';
        if ($list) {
            foreach ($list as $info) {
                $resultStr .= '[' . $info['name'] . ']';
            }
        }
        return $resultStr;
    }
    
    /**
     * 获取品牌分类名称
     * @param type $categoryIds
     * @return string
     */
    public function getBrandCategoryName($categoryIds){
        $model = new BrandCategory();
        $list = $model->find()
            ->select('name')
            ->where('id in ('.$categoryIds.')')
            ->all();
        $brandCategoryName = '';
        if($list){
            foreach ($list as $info){
                $brandCategoryName .= $info['name'] . ' ';
            }
        }
        return $brandCategoryName;
    }

}
