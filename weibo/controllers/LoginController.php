<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace weibo\controllers;

use yii\web\Controller;

/**
 * Description of LoginController
 *
 * @author baihua <baihua_2011@163.com>
 */
class LoginController extends Controller {

    public function actionIndex() {
        return $this->renderPartial('index');
    }
    
    public function actionSignin(){
        pprint($_POST);
    }

}
