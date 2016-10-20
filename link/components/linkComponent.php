<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace link\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use link\models\Category;
use link\models\Content;

/**
 * Description of linkComponent
 *
 * @author baihua <baihua_2011@163.com>
 */
class linkComponent extends Component {

    /**
     * 所有一级分类
     * @return type
     */
    public function getCategoryListTop() {
        $categoryModel = new Category();
        $categoryList = $categoryModel->find()
            ->select(array('id', 'name'))
            ->where('parent_id = 0 and visibility = 1')
            ->orderBy('sort desc')
            ->all();
        return $categoryList;
    }

    /**
     * 根据一级分类输出二级分类列表
     * @param type $parentId
     * @return type
     */
    public function getCategoryByParentid($parentId) {
        // 尝试从缓存中取回数据
        $categoryData = Yii::$app->cache->get('category_data_' . $parentId);
        if (false == $categoryData) {
            $categoryModel = new Category();
            $categoryList = $categoryModel->find()
                ->select(array('id', 'name'))
                ->where('parent_id = :parentId and visibility = 1', [':parentId' => $parentId])
                ->orderBy('sort desc')
                ->all();
            if ($categoryList) {
                //缓存数据
                Yii::$app->cache->set('category_data_' . $parentId, Json::encode(ArrayHelper::toArray($categoryList)), 24 * 3600);
            }
        } else {
            $categoryList = Json::decode($categoryData);
        }
        return $categoryList;
    }

    /**
     * 分类下所有链接
     * @param type $categoryId
     * @return type
     */
    public function getLinkByCategoryId($categoryId) {
        // 尝试从缓存中取回数据
        $contentData = Yii::$app->cache->get('content_data_' . $categoryId);
        if (false == $contentData) {
            $contentModel = new Content();
            $contentList = $contentModel->find()
                ->select(array('id', 'name', 'ico_path', 'url'))
                ->where('category_id = :categoryId and visibility = 1', [':categoryId' => $categoryId])
                ->orderBy('sort desc')
                ->all();
            if ($contentList) {
                //缓存数据
                Yii::$app->cache->set('content_data_' . $categoryId, Json::encode(ArrayHelper::toArray($contentList)), 24 * 3600);
            }
        } else {
            $contentList = Json::decode($contentData);
        }
        return $contentList;
    }

}
