<?php

namespace backend\controllers;

use Yii;
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

}
