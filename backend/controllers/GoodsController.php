<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\Goods;
use common\models\Attribute;
use common\models\Model;
use common\models\Spec;
use common\models\SpecPhoto;
use backend\logics\GoodsLogic;

/**
 * Description of GoodsController
 *
 * @author baihua <baihua_2011@163.com>
 */
class GoodsController extends BaseController {

    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    /* 上传图片配置项 */
                    "imageUrlPrefix" => "http://img.yii2shop.com", //图片访问路径前缀
                    "imagePathFormat" => "/upload/images/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                    /* 涂鸦图片上传配置项 */
                    'scrawlUrlPrefix' => 'http://img.yii2shop.com',
                    'scrawlPathFormat' => '/upload/images/{yyyy}{mm}{dd}/{time}{rand:6}',
                    /* 截图工具上传 */
                    'snapscreenUrlPrefix' => 'http://img.yii2shop.com',
                    'snapscreenPathFormat' => '/upload/images/{yyyy}{mm}{dd}/{time}{rand:6}',
                    /* 抓取远程图片配置 */
                    'catcherUrlPrefix' => 'http://img.yii2shop.com',
                    'catcherPathFormat' => '/upload/images/{yyyy}{mm}{dd}/{time}{rand:6}',
                    /* 上传视频配置 */
                    'videoUrlPrefix' => 'http://img.yii2shop.com',
                    'videoPathFormat' => '/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}',
                ],
            ]
        ];
    }

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

    /**
     * 添加商品
     * @return type
     */
    public function actionCreate() {
        $this->getBaseData();
        return $this->render('create');
    }

    /**
     * 编辑商品
     * @return type
     */
    public function actionEdit() {
        $this->getBaseData('goods', 'list');
        $goodsId = intval(Yii::$app->request->get('id'));
        //初始化数据
        $goodsLogic = new GoodsLogic();
        //获取所有商品扩展相关数据
        $data = $goodsLogic->edit($goodsId);
        return $this->render('create', $data);
    }

    /**
     * 商品分类弹框
     * @return type
     */
    public function actionGoodsCategory() {
        $type = Yii::$app->request->get('type');
        $data['type'] = empty($type) ? 'radio' : $type;
        return $this->renderPartial('goodsCategory', $data);
    }

    /**
     * ajax添加商品扩展属性
     */
    public function actionAttributeInit() {
        $modelId = intval(Yii::$app->request->get('model_id'));
        $model = new Attribute();
        $list = $model->find()->where('model_id=:modelId', [':modelId' => $modelId])->all();
        //JSON 响应
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $list;
    }

    /**
     * 添加给商品规格
     * @return type
     */
    public function actionSearchSpec() {
        $data = array();
        //获得model_id的值
        $modelId = intval(Yii::$app->request->get('model_id'));
        $goodsId = intval(Yii::$app->request->get('goods_id'));
        $specId = '';

        if ($goodsId) {
            $goodsModel = new Goods();
            $goodsInfo = $goodsModel->find()
                ->select(['spec_array'])
                ->where('id=:goodsId', [':goodsId' => $goodsId])
                ->one();
            $data['goodsSpec'] = json_decode($goodsInfo['spec_array'], true);
            if ($data['goodsSpec']) {
                foreach ($data['goodsSpec'] as $item) {
                    $specId .= $item['id'] . ',';
                }
            }
        } else if ($modelId) {
            $modelModel = new Model();
            $modelInfo = $modelModel->find()
                ->select(['spec_ids'])
                ->where('id=:modelId', [':modelId' => $modelId])
                ->one();
            $specId = $modelInfo['spec_ids'] ? $modelInfo['spec_ids'] : '';
        }

        if ($specId) {
            $specModel = new Spec();
            $specInfo = $specModel->find()
                ->where('id in (' . trim($specId, ',') . ')')
                ->all();
            $data['specData'] = \yii\helpers\ArrayHelper::toArray($specInfo);
        }
        return $this->renderPartial('searchSpec', $data);
    }

    /**
     * 选择规格数据
     * @return type
     */
    public function actionSelectSpec() {
        $specModel = new Spec();
        $specInfo = $specModel->find()
            ->where('is_del=0')
            ->all();
        return $this->renderPartial('selectSpec', ['specInfo' => Json::encode($specInfo)]);
    }

    /**
     * 规格编辑
     * @return type
     */
    public function actionSpecEdit() {
        $specId = intval(Yii::$app->request->get('id'));

        $data = array(
            'id' => '',
            'name' => '',
            'type' => '',
            'value' => '',
            'note' => '',
        );

        if ($specId) {
            $model = new Spec();
            $specInfo = $model->find()->where('id=:specId', [':specId' => $specId])->one();
            if ($specInfo) {
                $data = ArrayHelper::toArray($specInfo);
            }
        }
        return $this->renderPartial('specEdit', $data);
    }

    /**
     * 增加或者修改规格
     */
    public function actionSpecUpdate() {
        $id = intval($this->getParams('id'));
        $name = $this->getParams('name');
        $specType = $this->getParams('type');
        $value = $this->getParams('value');
        $note = $this->getParams('note');

        //要插入的数据
        if (is_array($value) && isset($value[0]) && $value[0]) {
            $value = array_filter($value);
            $value = array_unique($value);
            $value = $value ? json_encode($value) : '';
        }

        if (!$name) {
            die(json_encode(array('flag' => 'fail', 'message' => '规格名称不能为空')));
        }

        if (!$value) {
            die(json_encode(array('flag' => 'fail', 'message' => '规格值不能为空,请填写规格值或上传规格图片')));
        }

        $editData = array(
            'id' => $id,
            'name' => $name,
            'value' => $value,
            'type' => $specType,
            'note' => $note,
        );

        //执行操作
        $obj = new Spec();

        //更新修改
        if ($id) {
            $info = $obj->find()->where('id=:specId', [':specId' => $id])->one();
            $info->name = $editData['name'];
            $info->value = $editData['value'];
            $info->type = $editData['type'];
            $info->note = $editData['note'];
            $result = $info->update();
        }
        //添加插入
        else {
            $obj->name = $editData['name'];
            $obj->value = $editData['value'];
            $obj->type = $editData['type'];
            $obj->note = $editData['note'];
            $obj->save();
            $result = $obj->id;
        }

        //执行状态
        if ($result === false) {
            die(json_encodee(array('flag' => 'fail', 'message' => '数据库更新失败')));
        } else {
            //获取自动增加ID
            $editData['id'] = $id ? $id : $result;
            die(json_encode(array('flag' => 'success', 'data' => $editData)));
        }
    }

    /**
     * Ajax获取规格值
     * @return type
     */
    public function actionSpecValueList() {
        // 获取POST数据
        $specId = intval(Yii::$app->request->get('id'));

        //初始化spec商品模型规格表类对象
        $specObj = new Spec();
        //根据规格编号 获取规格详细信息
        $specData = $specObj->find()->where('id=:specId', [':specId' => $specId])->one();
        //JSON 响应
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($specData) {
            return $specData;
        } else {
            //返回失败标志
            return '';
        }
    }

    /**
     * 保存修改商品信息
     */
    public function actionUpdate() {
        $goodsId = intval(Yii::$app->request->post('id'));

        //初始化商品数据
        unset($_POST['id']);
        unset($_POST['callback']);

        $goodsLogic = new GoodsLogic();
        $goodsLogic->update($goodsId, $_POST);

        $this->redirect(Url::to(['/goods/list']));
    }

    /**
     * 规格列表
     */
    public function actionSpecList() {
        $this->getBaseData('model', 'spec-list');
        $data = Spec::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $model = $data->select(['id', 'name', 'value', 'type', 'note', 'is_del'])
            ->where('is_del=0')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('specList', [
                'model' => $model,
                'pages' => $pages,
        ]);
    }

    /**
     * 批量删除规格
     */
    public function actionSpecDel() {
        $specId = intval(Yii::$app->request->get('id'));
        if ($specId) {
            $model = new Spec();
            if (is_array($specId)) {
                $where = "id in (" . join(',', $specId) . ")";
            } else {
                $where = 'id = ' . $specId;
            }
            $model->updateAll(['is_del' => 1], $where);
        }
        $this->redirect(Url::to(['/goods/spec-list']));
    }

    /**
     * 规格图库
     */
    public function actionSpecPhoto() {
        $this->getBaseData('model', 'spec-photo');
        $data = SpecPhoto::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $model = $data->select(['id', 'name', 'address', 'create_time'])
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('specPhoto', [
                'model' => $model,
                'pages' => $pages,
        ]);
    }

    /**
     * 规格图片删除
     */
    public function actionSpecPhotoDel() {
        $idArr = Yii::$app->request->post('ids');
        $resultArr = ['errcode' => 1, 'errmsg' => '删除失败'];
        if (is_array($idArr)) {
            $idStr = join(',', $idArr);
            $where = ' id in (' . $idStr . ')';
            $model = new SpecPhoto();
            $result = $model->deleteAll($where);
            if ($result) {
                $resultArr = ['errcode' => 0, 'errmsg' => '删除成功'];
            }
        }
        echo Json::encode($resultArr);
    }

}
