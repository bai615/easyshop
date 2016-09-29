<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use yii\data\Pagination;
use common\models\Model;
use common\models\GoodsAttribute;
use backend\logics\ModelLogic;

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

    /**
     * 创建模型
     * @return type
     */
    public function actionCreate() {
        $this->getBaseData('model', 'list');
        return $this->render('edit');
    }

    /**
     * 编辑模型
     * @return type
     */
    public function actionEdit() {
        $this->getBaseData('model', 'list');
        $modelId = intval(Yii::$app->request->get('id'));
        $modelInfo = array();
        if ($modelId) {
            $modelLogic = new ModelLogic();
            $modelInfo = $modelLogic->getModelInfo($modelId);
        }
        return $this->render('edit', ['modelInfo' => $modelInfo]);
    }

    /**
     * 商品模型添加/修改
     */
    public function actionUpdate() {
        // 获取POST数据
        $modelId = intval(Yii::$app->request->post('id'));
        $modelName = Yii::$app->request->post('name');
        $attribute = Yii::$app->request->post('attr');

        //初始化Model逻辑类对象
        $modelLogic = new ModelLogic();

        //更新模型数据
        $result = $modelLogic->updateModel($modelId, $modelName, $attribute);
        if ($result) {
            $this->redirect(Url::to(['/model/list']));
        } else {
            //处理post数据，渲染到前台
            $result = $modelLogic->postArrayChange($attribute);
            $modelInfo = array(
                'id' => $modelId,
                'name' => $modelName,
                'model_attr' => $result['model_attr'],
            );
            $this->getBaseData('model', 'list');
            return $this->render('edit', ['modelInfo' => $modelInfo]);
        }
    }

    /**
     * 删除模型
     */
    public function actionRemove() {
        $modelId = intval(Yii::$app->request->get('id'));
        $modelId = is_array($modelId) ? $modelId : array($modelId);
        if ($modelId) {
            foreach ($modelId as $key => $val) {
                //初始化goods_attribute表类对象
                $goodsAttrObj = new GoodsAttribute();

                //获取商品属性表中的该模型下的数量
                $attrData = $goodsAttrObj->find()->where("model_id = " . $val)->one();
                pprint($attrData);
                if ($attrData) {
                    $url = Url::to(['/model/list']);
                    $this->redirect(Url::to(['/common/message', 'message' => '无法删除此模型，请确认该模型下无商品', 'url' => $url]));
                }

                //初始化Model逻辑类对象
                $modelLogic = new ModelLogic();

                //删除商品模型
                $modelLogic->delModel($val);
            }
        }
        $this->redirect(Url::to(['/model/list']));
    }

}
