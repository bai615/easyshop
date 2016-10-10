<?php

namespace backend\controllers;

use Yii;
use common\models\Areas;

/**
 * 后台管理公共模块
 *
 * @author baihua <baihua_2011@163.com>
 */
class CommonController extends BaseController {

    /**
     * 上传图片弹出框
     */
    public function actionPic() {
        $data['specIndex'] = intval(Yii::$app->request->get('specIndex'));
        return $this->renderPartial('pic', $data);
    }

    /**
     * 信息展示
     * @return type
     */
    public function actionMessage() {
        $this->getBaseData('', '');
        $data['message'] = Yii::$app->request->get('message');
        $data['url'] = Yii::$app->request->get('url');
        return $this->render('message', $data);
    }

    /**
     * 获取地区
     */
    public function actionAreaChild() {
        $parentId = intval(Yii::$app->request->get('aid'));
        $areaModel = new Areas();
        $areaList = $areaModel->find()
            ->select(['area_id', 'parent_id', 'area_name', 'sort'])
            ->where('parent_id=:parentId', [':parentId' => $parentId])
            ->orderBy('sort asc')
            ->all();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($areaList) {
            return ($areaList);
        }
    }

}
