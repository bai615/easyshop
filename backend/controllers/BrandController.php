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
    public function actionCategory() {
        $this->getBaseData();
        $data = BrandCategory::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $model = $data->select(['id', 'name', 'goods_category_id'])
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('categoryList', [
                'model' => $model,
                'pages' => $pages,
        ]);
    }

    /**
     * 创建品牌分类
     * @return type
     */
    public function actionCreateCategory() {
        $this->getBaseData('brand', 'category');
        return $this->render('editCategory');
    }

    /**
     * 编辑品牌分类
     * @return type
     */
    public function actionEditCategory() {
        $this->getBaseData('brand', 'category');
        $categoryId = intval(Yii::$app->request->get('id'));
        $categoryInfo = array();
        if ($categoryId) {
            $brandCategoryModel = new BrandCategory();
            $categoryInfo = $brandCategoryModel->find()->where('id=:categoryId', [':categoryId' => $categoryId])->one();
        }
        return $this->render('editCategory', ['categoryInfo' => $categoryInfo]);
    }

    /**
     * 保存品牌分类
     */
    public function actionCategorySave() {
        //获得post值
        $categoryId = intval(Yii::$app->request->post('id'));
        $name = Yii::$app->request->post('name');
        $goodsCategoryId = intval(Yii::$app->request->post('goods_category_id'));

        if (empty($name)) {
            $this->redirect(Url::to(['/brand/category-list']));
        }

        $categoryData = array(
            'name' => $name,
            'goods_category_id' => $goodsCategoryId
        );
        $brandCategoryModel = new BrandCategory();

        //更新品牌分类
        if ($categoryId) {
            $brandCategoryModel->updateAll($categoryData, 'id=:categoryId', [':categoryId' => $categoryId]);
        }
        //添加品牌分类
        else {
            foreach ($categoryData as $key => $value) {
                $brandCategoryModel->$key = $value;
            }
            $brandCategoryModel->save();
        }
        $this->redirect(Url::to(['/brand/category']));
    }

    /**
     * 删除品牌分类
     * @return type
     */
    public function actionDelCategory() {
        $categoryId = intval(Yii::$app->request->get('id'));
        $brandCategoryModel = new BrandCategory();
        $brandCategoryInfo = $brandCategoryModel->find()->where('id=:categoryId', [':categoryId' => $categoryId])->one();
        if ($brandCategoryInfo) {
            $brandCategoryModel->deleteAll('id=:categoryId', [':categoryId' => $categoryId]);
        }
        $this->redirect(Url::to(['/brand/category']));
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

    /**
     * 创建品牌
     * @return type
     */
    public function actionCreate() {
        $this->getBaseData('brand', 'list');
        return $this->render('edit');
    }

    /**
     * 编辑品牌
     * @return type
     */
    public function actionEdit() {
        $this->getBaseData('brand', 'list');
        $brandId = intval(Yii::$app->request->get('id'));
        $brandInfo = array();
        if ($brandId) {
            $brandModel = new Brand();
            $brandInfo = $brandModel->find()->where('id=:brandId', [':brandId' => $brandId])->one();
        }
        return $this->render('edit', ['brandInfo' => $brandInfo]);
    }

    /**
     * 保存品牌
     */
    public function actionSave() {
        $brandId = intval(Yii::$app->request->post('id'));
        $name = Yii::$app->request->post('name');
        $sort = intval(Yii::$app->request->post('sort'));
        $url = Yii::$app->request->post('url');
        $category = Yii::$app->request->post('category');
        $description = Yii::$app->request->post('description');

        $brandModel = new Brand();
        $brandData = array(
            'name' => $name,
            'sort' => $sort,
            'url' => $url,
            'description' => $description,
        );

        if ($category && is_array($category)) {
            $categorys = join(',', $category);
            $brandData['category_ids'] = $categorys;
        } else {
            $brandData['category_ids'] = '';
        }
        if ($brandId) {
            //保存修改分类信息
            $brandModel->updateAll($brandData, 'id=:brandId', [':brandId' => $brandId]);
        } else {
            //添加新品牌
            foreach ($brandData as $key => $value) {
                $brandModel->$key = $value;
            }
            $brandModel->save();
        }
        $this->redirect(Url::to(['/brand/list']));
    }

    /**
     * 删除品牌
     */
    public function actionRemove() {
        $brandId = intval(Yii::$app->request->get('id'));
        $brandModel = new Brand();
        $brandInfo = $brandModel->find()->where('id=:brandId', [':brandId' => $brandId])->one();
        if ($brandInfo) {
            $brandModel->deleteAll('id=:brandId', [':brandId' => $brandId]);
        }
        $this->redirect(Url::to(['/brand/list']));
    }

}
