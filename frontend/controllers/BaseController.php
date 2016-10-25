<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use common\utils\CommonTools;

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

        //自动登录检测
        $cookieName = CommonTools::getAutoCookieName();
        $cookies = Yii::$app->request->cookies;
        //获取cookie自动登录信息
        if (($cookie = $cookies->get($cookieName)) !== null) {
            $cookieStr = $cookie->value;
            //数据解密
            $autoInfoStr = CommonTools::encrypt($cookieStr, 'D');
            $autoInfoArr = explode('|', $autoInfoStr);
            //获取当前IP值
            $newIP = CommonTools::real_ip();
            if (ip2long($newIP) != ip2long($autoInfoArr['1'])) {
                //非同一个IP登录，需要重新登录
            } else {
                //进行自动登录操作
                $userInfo = \common\models\User::findByUserName($autoInfoArr['0']);
                $data = array(
                    'userId' => $userInfo['id'],
                    'userName' => $userInfo['username'],
                    'head_ico' => $userInfo['head_ico'],
                );
                $this->data['shopUserInfo'] = $data;
                //保存session信息
                Yii::$app->session['shopUserInfo'] = $data;
            }
        }
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
