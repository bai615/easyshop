<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use yii\web\Controller;
use yii\data\Pagination;
use common\models\Goods;

/**
 * Description of GoodsController
 *
 * @author baihua <baihua_2011@163.com>
 */
class GoodsController extends Controller {

    public function actionList() {
        $data = Goods::find(); //->andWhere(['id' => '10']);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $model = $data->select(['id', 'name', 'sell_price', 'market_price', 'store_nums', 'img', 'is_del'])
            ->orderBy('id desc')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
//        pprint($model);

        return $this->render('list', [
                'model' => $model,
                'pages' => $pages,
        ]);
    }

}
