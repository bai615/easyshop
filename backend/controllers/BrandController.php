<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use yii\data\Pagination;
use common\models\BrandCategory;
use common\models\Brand;

/**
 * Description of BrandController
 *
 * @author baihua <baihua_2011@163.com>
 */
class BrandController extends BaseController {
    
    /**
     * 品牌分类列表
     * @return type
     */
    public function actionCategory(){
        $this->getBaseData();
        $data = BrandCategory::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $model = $data->select(['id', 'name'])
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('categoryList', [
                'model' => $model,
                'pages' => $pages,
        ]);
    }

    /**
     * 品牌列表
     * @return type
     */
    public function actionList() {
        $this->getBaseData();
        $data = Brand::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $model = $data->select(['id', 'name', 'url', 'category_ids', 'sort'])
            ->orderBy('sort asc')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('list', [
                'model' => $model,
                'pages' => $pages,
        ]);
    }

}
