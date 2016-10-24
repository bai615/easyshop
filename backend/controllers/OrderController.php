<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use common\models\Order;
use common\models\Areas;
use common\models\FreightCompany;

/**
 * Description of OrderController
 *
 * @author baihua <baihua_2011@163.com>
 */
class OrderController extends BaseController {

    /**
     * 订单列表
     * @return type
     */
    public function actionList() {
        $this->getBaseData();
        $data = Order::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $model = $data->select(['id', 'order_no', 'accept_name', 'pay_status', 'distribution_status', 'pay_type', 'user_id', 'create_time'])
            ->where('is_del != 1')
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
     * 订单创建
     */
    public function actionCreate() {
        //第一版暂时不支持此功能
    }

    /**
     * 订单编辑
     */
    public function actionEdit() {
        //第一版暂时不支持此功能
    }

    public function actionView() {
        $this->getBaseData('order', 'list');
        $orderId = intval(Yii::$app->request->get('id'));
        //订单信息
        $orderModel = new Order();
        $orderInfo = $orderModel->find()
            ->where('id=:orderId', [':orderId' => $orderId])
            ->one();
        //地址信息
        $areaData = Areas::name($orderInfo['province'], $orderInfo['city'], $orderInfo['area']);
        return $this->render('view', ['orderInfo' => $orderInfo, 'areaData' => $areaData]);
    }

    /**
     * 发货窗口
     */
    public function actionDeliver() {
        $orderId = intval(Yii::$app->request->get('id'));
        //订单信息
        $orderModel = new Order();
        $orderInfo = $orderModel->find()
            ->where('id=:orderId', [':orderId' => $orderId])
            ->one();
        //地址信息
        $areaData = Areas::name($orderInfo['province'], $orderInfo['city'], $orderInfo['area']);
        return $this->renderPartial('deliver', ['orderInfo' => $orderInfo, 'areaData' => $areaData]);
    }

    /**
     * 发货保存
     */
    public function actionDeliverSave() {
        $orderId = intval(Yii::$app->request->post('id'));
        $freightId = Yii::$app->request->post('freight_id');
        $deliveryCode = Yii::$app->request->post('delivery_code');

        $orderModel = new Order();
        
        $model = new FreightCompany();
        $info = $model->find()->select(['freight_name'])->where('id=:freightId', [':freightId' => $freightId])->one();
        $data = [
            'distribution_status' => 1,
            'send_time' => date('Y-m-d H:i:s'),
            'freight_name' => empty($info) ? '' : $info['freight_name'],
            'delivery_code' => $deliveryCode
        ];
        
        $orderModel->updateAll($data, 'id=:orderId', [':orderId' => $orderId]);
        die('<script type="text/javascript">parent.actionCallback();</script>');
    }

}
