<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use common\utils\GeetestLib;
use frontend\models\LoginForm;
use frontend\logics\UserLogic;

/**
 * 用户（登录、注册、退出）
 *
 * @author baihua <baihua_2011@163.com>
 */
class UsersController extends BaseController {

    /**
     * 注册
     */
    public function actionSignup() {
        $data = array();
        if (Yii::$app->request->post()) {
            $data['type'] = Yii::$app->request->post('type');
            $data['username'] = Yii::$app->request->post('mobile');
            $data['password'] = Yii::$app->request->post('password');
            $data['repassword'] = Yii::$app->request->post('repassword');
            $data['agreen'] = Yii::$app->request->post('agreen');
            $data['geetest_challenge'] = Yii::$app->request->post('geetest_challenge');
            $data['geetest_validate'] = Yii::$app->request->post('geetest_validate');
            $data['geetest_seccode'] = Yii::$app->request->post('geetest_seccode');

            $userLogic = new UserLogic();
            $data['errmsg'] = $userLogic->signUpAct($data);
            if ('OK' == $data['errmsg']) {
                $this->redirect(Url::to(['/common/success', 'message' => '注册成功']));
            }
        }
        return $this->render('signup', $data);
    }

    /**
     * 初始化验证码
     */
    public function actionStart() {
        $type = Yii::$app->request->get('type');
        if ('pc' == $type) {
            $GtSdk = new GeetestLib(Yii::$app->params['geetest_info']['captcha_id'], Yii::$app->params['geetest_info']['private_key']);
        } elseif ('mobile' == $type) {
            $GtSdk = new GeetestLib(Yii::$app->params['geetest_info']['mobile_captcha_id'], Yii::$app->params['geetest_info']['mobile_private_key']);
        }
        $user_id = "geetest_test";
        $status = $GtSdk->pre_process($user_id);
        Yii::$app->session['geetest_gtserver'] = $status;
        Yii::$app->session['geetest_user_id'] = $user_id;
        echo $GtSdk->get_response_str();
    }

    /**
     * 检查用户是否已存在
     */
    public function actionCheckName() {
        $username = Yii::$app->request->post('mobile');
        $flag = UserLogic::checkByName($username);
        if ($flag) {
            echo json_encode(['errcode' => 1, 'errmsg' => '您的手机已被占用']);
        } else {
            echo json_encode(['errcode' => 0, 'errmsg' => 'OK']);
        }
    }

    /**
     * 登录
     */
    public function actionLogin() {
        $callback = Yii::$app->request->get('callback');
        //跳转URL
        $callbackUrl = empty($callback) ? Url::to(['/site/index']) : $callback;
        $session = Yii::$app->session;
        $shopUserInfo = $session->get('shopUserInfo');
        if ($shopUserInfo) {
            //已经登录，进行跳转
            $this->redirect($callbackUrl);
        }
        $data = array();
        $model = new LoginForm();
        if (Yii::$app->request->post()) {
            $data['type'] = Yii::$app->request->post('type');
            $data['geetest_challenge'] = Yii::$app->request->post('geetest_challenge');
            $data['geetest_validate'] = Yii::$app->request->post('geetest_validate');
            $data['geetest_seccode'] = Yii::$app->request->post('geetest_seccode');
            $userLogic = new UserLogic();
            //校验验证码
            $result = $userLogic->checkVerify($data);
            if (0 == $result['errcode'] && $model->load(Yii::$app->request->post()) && $model->login()) {
                //登录成功进行跳转
                $this->redirect($callbackUrl);
            } else if (1 == $result['errcode']) {
                $data['errmsg'] = $result['errmsg'];
            }
        }
        $data['model'] = $model;
        return $this->render('login', $data);
    }

    /**
     * 退出登录
     */
    public function actionLogout() {
        $callback = Yii::$app->request->get('callback');
        //跳转URL
        $callbackUrl = empty($callback) ? Url::to(['/site/index']) : $callback;
        UserLogic::logout();
        //已经退出登录，进行跳转
        $this->redirect($callbackUrl);
    }

}
