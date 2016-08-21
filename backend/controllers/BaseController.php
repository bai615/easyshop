<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use yii\web\Controller;

/**
 * Description of BaseController
 *
 * @author baihua <baihua_2011@163.com>
 */
class BaseController extends Controller {

    public $layoutData = [];
    
    public function init() {
        parent::init();
    }

    public function getBaseData() {
        $this->layoutData['controller'] = $this->id;
        $this->layoutData['action'] = $this->id . '-' . $this->action->id;
    }

}
