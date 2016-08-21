<?php

namespace backend\controllers;

use Yii;
use backend\models\CreateAdminForm;

/**
 * Description of AdminController
 *
 * @author baihua <baihua_2011@163.com>
 */
class AdminController extends BaseController {

    public function actionCreate() {
        $this->getBaseData();
        $model = new CreateAdminForm();
        
        if ($model->load(Yii::$app->request->post())) {
            if ($admin = $model->createAdmin()) {
//                if (Yii::$app->getUser()->login($admin)) {
//                    return $this->goHome();
//                }
            }
        }

        return $this->render('create', [
                'model' => $model,
        ]);
    }

}
