<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

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
    }

    /**
     * 是否登录
     */
    public function is_login() {
        if (empty($this->data['shopUserInfo'])) {
            $callBackUrl = Url::to([$this->id . '/' . $this->action->id]) . '?' . http_build_query($_GET);
            $this->redirect(Url::to(['users/login', 'callback' => $callBackUrl]));
        }
    }

}
