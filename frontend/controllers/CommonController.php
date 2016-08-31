<?php

namespace frontend\controllers;

use Yii;

/**
 * 公共模块
 *
 * @author baihua <baihua_2011@163.com>
 */
class CommonController extends BaseController {

    /**
     * 成功提示页
     */
    public function actionSuccess() {
        $data['message'] = Yii::$app->request->get('message');
        return $this->render('success', $data);
    }

}
