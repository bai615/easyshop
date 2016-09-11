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
use common\models\Goods;
use common\models\Attribute;
use common\models\Model;
use common\models\Spec;

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
                    "imageUrlPrefix" => "http://img.yii2shop.com", //图片访问路径前缀
                    "imagePathFormat" => "images/upload/{yyyy}{mm}{dd}/{time}{rand:6}" //上传保存路径
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

    public function actionCreate() {
        $this->getBaseData();
        return $this->render('create', [
        ]);
    }

    /**
     * 商品分类弹框
     * @return type
     */
    public function actionGoodsCategory() {
        return $this->renderPartial('goodsCategory');
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

    public function actionSpecEdit() {
        $id = intval(Yii::$app->request->get('id'));

        $data = array(
            'id' => '',
            'name' => '',
            'type' => '',
            'value' => '',
            'note' => '',
        );

        if ($id) {
//            $obj = new IModel('spec');
//            $dataRow = $obj->getObj("id = {$id}");
        }
        return $this->renderPartial('specEdit', $data);
    }

    /**
     * 增加或者修改规格
     */
    public function actionSpecUpdate() {
        $id = intval(Yii::$app->request->get('id'));
        $name = Yii::$app->request->get('name');
        $specType = Yii::$app->request->get('type');
        $value = Yii::$app->request->get('value');
        $note = Yii::$app->request->get('note');

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

    public function actionTest() {
        pprint($_SERVER['DOCUMENT_ROOT']);
        $this->getBaseData('goods', 'create');
        $model = new Goods();
        return $this->render('test', ['model' => $model]);
    }

}
