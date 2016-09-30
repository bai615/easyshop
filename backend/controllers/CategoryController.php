<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use common\models\Category;
use common\models\CategoryExtend;
use backend\logics\GoodsLogic;

/**
 * Description of Category
 *
 * @author baihua <baihua_2011@163.com>
 */
class CategoryController extends BaseController {

    /**
     * 分类列表
     * @return type
     */
    public function actionList() {
        $this->getBaseData();
        $model = new Category();
        $data = $model->find()->orderBy('sort desc')->all();
        $categoryInfo = GoodsLogic::sortdata($data);
        return $this->render('list', ['categoryInfo' => $categoryInfo]);
    }

    /**
     * 创建分类
     * @return type
     */
    public function actionCreate() {
        $this->getBaseData();
        return $this->render('create');
    }

    /**
     * 编辑分类
     * @return type
     */
    public function actionEdit() {
        $this->getBaseData('category', 'list');
        $categoryId = intval(Yii::$app->request->get('id'));
        $categoryInfo = array();
        if ($categoryId) {
            $categoryModel = new Category();
            $categoryInfo = $categoryModel->find()->where('id=:categoryId', [':categoryId' => $categoryId])->one();
        }
        return $this->render('create', ['categoryInfo' => $categoryInfo]);
    }

    /**
     * 保存商品分类
     */
    public function actionSave() {
        //获得post值
        $categoryId = intval(Yii::$app->request->post('id'));
        $name = Yii::$app->request->post('name');
        $parentId = intval(Yii::$app->request->post('parent_id'));
        $visibility = intval(Yii::$app->request->post('visibility'));
        $sort = intval(Yii::$app->request->post('sort'));

        if (empty($name)) {
            $this->redirect(Url::to(['/category/list']));
        }

        $categoryModel = new Category();
        $categoryData = array(
            'name' => $name,
            'parent_id' => $parentId,
            'sort' => $sort,
            'visibility' => $visibility,
        );
        if ($categoryId) {         //保存修改分类信息
            $categoryModel->updateAll($categoryData, 'id=:categoryId', [':categoryId' => $categoryId]);
        } else {            //添加新商品分类
            foreach ($categoryData as $key => $value) {
                $categoryModel->$key = $value;
            }
            $categoryModel->save();
        }
        $this->redirect(Url::to(['/category/list']));
    }

    /**
     * 删除分类
     */
    public function actionRemove() {
        $categoryId = intval(Yii::$app->request->get('id'));
        if ($categoryId) {
            $categoryModel = new Category();
            $catInfo = $categoryModel->find()->where('parent_id = :categoryId', [':categoryId' => $categoryId]);

            //要删除的分类下还有子节点
            if (!empty($catInfo)) {
                $url = Url::to(['/category/list']);
                $this->redirect(Url::to(['/common/message', 'message' => '无法删除此分类，此分类下还有子分类', 'url' => $url]));
            }

            $result = $categoryModel->deleteAll('id=:categoryId', [':categoryId' => $categoryId]);
            if ($result) {
                $categoryExtendModel = new CategoryExtend();
                $categoryExtendModel->deleteAll('category_id=:categoryId', [':categoryId' => $categoryId]);
            }
        }
        $this->redirect(Url::to(['/category/list']));
    }

}
