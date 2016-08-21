<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use yii\data\Pagination;
use common\models\Goods;

/**
 * Description of GoodsController
 *
 * @author baihua <baihua_2011@163.com>
 */
class GoodsController extends BaseController {

    /**
     * 商品列表
     * @return type
     */
    public function actionList() {
        $this->getBaseData();
        $data = Goods::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $model = $data->select(['id', 'name', 'sell_price', 'market_price', 'store_nums', 'img', 'is_del'])
            ->orderBy('id desc')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('list', [
                'model' => $model,
                'pages' => $pages,
        ]);
    }
    
    public function actionCreate(){
        $this->getBaseData();
        return $this->render('create', [
        ]);
    }

}
