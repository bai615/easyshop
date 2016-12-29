<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace weibo\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
/**
 * Description of BaseController
 *
 * @author baihua <baihua_2011@163.com>
 */
class BaseController extends Controller {
    public $data = array();

    public function init() {
        parent::init();
        $session = Yii::$app->session;
        $this->data['shopUserInfo'] = $session->get('shopUserInfo');
        
        if (empty($this->data['shopUserInfo'])) {
            $this->redirect(Url::to(['/login/index']));
        }
    }
}
