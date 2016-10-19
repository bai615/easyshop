<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace link\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use link\models\Category;
use link\models\Content;
use yii\web\UploadedFile;
use link\models\UploadForm;

/**
 * Description of SiteController
 *
 * @author baihua <baihua_2011@163.com>
 */
class SiteController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * 分类列表
     */
    public function actionCategoryLis() {
        echo 'category-list';
    }

    /**
     * 创建分类
     * @return type
     */
    public function actionCreateCategory() {
        return $this->render('editCategory');
    }

    /**
     * 保存分类
     */
    public function actionSaveCategory() {
        //获得post值
        $categoryId = intval(Yii::$app->request->post('id'));
        $name = Yii::$app->request->post('name');
        $parentId = intval(Yii::$app->request->post('parent_id'));
        $visibility = intval(Yii::$app->request->post('visibility'));
        $sort = intval(Yii::$app->request->post('sort'));

        if (empty($name)) {
            $this->redirect(Url::to(['/site/category-list']));
        }

        $categoryModel = new Category();
        $categoryData = array(
            'name' => $name,
            'parent_id' => $parentId,
            'sort' => $sort,
            'visibility' => $visibility,
        );
        if ($categoryId) {
            //保存修改分类信息
            $categoryModel->updateAll($categoryData, 'id=:categoryId', [':categoryId' => $categoryId]);
        } else {
            //添加新分类
            foreach ($categoryData as $key => $value) {
                $categoryModel->$key = $value;
            }
            $categoryModel->save();
        }
        $this->redirect(Url::to(['/site/create-category']));
    }

    /**
     * 创建链接
     * @return type
     */
    public function actionCreateLink() {
        return $this->render('editLink');
    }

    /**
     * 保存链接
     */
    public function actionSaveLink() {

//        pprint($_POST);
//        pprint($_FILES);
        //http://www.ikjds.com/tools/index.html

        if (Yii::$app->request->isPost) {
            //获得post值
            $LinkId = intval(Yii::$app->request->post('id'));
            $name = Yii::$app->request->post('name');
            $categoryId = intval(Yii::$app->request->post('category_id'));
            $url = (Yii::$app->request->post('url'));
            $visibility = intval(Yii::$app->request->post('visibility'));
            $sort = intval(Yii::$app->request->post('sort'));

            if (empty($name)) {
                $this->redirect(Url::to(['/site/link-list']));
            }

            $model = new UploadForm();

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {
//                $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
                $filePath = 'uploads/' . date('YmdHis') . '_' . uniqid() . '.' . $model->file->extension;
                $model->file->saveAs($filePath);
            }

            $conentModel = new Content();
            $contentData = array(
                'name' => $name,
                'category_id' => $categoryId,
                'ico_path' => $filePath,
                'url' => $url,
                'sort' => $sort,
                'visibility' => $visibility,
            );

            if ($LinkId) {
                //保存修改分类信息
                $conentModel->updateAll($contentData, 'id=:linkId', [':linkId' => $LinkId]);
            } else {
                //添加新分类
                foreach ($contentData as $key => $value) {
                    $conentModel->$key = $value;
                }
                $conentModel->save();
            }
            $this->redirect(Url::to(['/site/create-link']));
        }
    }

    public function actionGetData() {
        set_time_limit(0);
        $file = (dirname(__DIR__)) . '/web/data.txt';
        $data = file_get_contents($file, true);
        pprint($data);

        preg_match_all('/<a href=\"(.*?)\".*?>(.*?)<\/a>/i', $data, $matches);
        foreach ($matches[2] as $key => $value) {
            $conentModel = new Content();
            $conentModel->name = strip_tags($value);
            $conentModel->url = ($matches[1][$key]);

            preg_match_all('/<img (.*?) data-original=\"(.*?)\".*?>(.*?)/i', $value, $imgInfo);

            if (preg_match("/^(http:\/\/|https:\/\/).*$/", $imgInfo[2][0])) {
                $imgScr = ($imgInfo[2][0]);
            } else {
                $imgScr = ('http://www.ikjds.com' . $imgInfo[2][0]);
            }
            pprint($imgScr);
            $imgData = file_get_contents($imgScr);
//            pprint(substr($imgScr, strrpos($imgScr, '.') + 1));
            $filePath = 'uploads/' . date('YmdHis') . '_' . uniqid() . (substr($imgScr, strrpos($imgScr, '.')));
            $conentModel->ico_path = $filePath;
            
            file_put_contents($filePath, $imgData);
            
            $conentModel->category_id = 37;
            $conentModel->sort = $key;
            
            $conentModel->save();
            pprint($filePath);
//            $imgData = file_get_contents($imgScr);
        }
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

}
