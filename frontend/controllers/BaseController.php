<?php

namespace frontend\controllers;

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
        
    }

}