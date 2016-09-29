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

    public function getBaseData($controller = '', $action = '') {
        $this->layoutData['controller'] = ($controller) ? $controller : $this->id;
        $this->layoutData['action'] = (($controller) && ($action)) ? $controller . '-' . $action : $this->id . '-' . $this->action->id;
    }

    /**
     * 获取键为$key的 $_GET 和 $_POST 传送方式的数据
	 * @param string $key $_GET 或 $_POST 的键
	 * @param string $type 传送方式 值: false:默认(先get后post); get:get方式; post:post方式;
	 * @return string $_GET 或者 $_POST 的值
	 * @note 优先获取 $_GET 方式的数据,如果不存在则获取 $_POST 方式的数据
     */
    public function getParams($key, $type = false) {
        //默认方式
        if ($type == false) {
            if (isset($_GET[$key]))
                return $_GET[$key];
            else if (isset($_POST[$key]))
                return $_POST[$key];
            else
                return null;
        }

        //get方式
        else if ($type == 'get' && isset($_GET[$key])) {
            return $_GET[$key];
        }
        //post方式
        else if ($type == 'post' && isset($_POST[$key])) {
            return $_POST[$key];
        }
        //无匹配
        else {
            return null;
        }
    }

}
