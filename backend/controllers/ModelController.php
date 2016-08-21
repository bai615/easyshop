<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use yii\data\Pagination;
use common\models\Model;

/**
 * Description of ModelController
 *
 * @author baihua <baihua_2011@163.com>
 */
class ModelController extends BaseController {

    /**
     * 模型列表
     * @return type
     */
    public function actionList() {
        $this->getBaseData();
        $data = Model::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $model = $data->select(['id', 'name'])
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('list', [
                'model' => $model,
                'pages' => $pages,
        ]);
    }

}
