<?php

namespace frontend\controllers;

use Yii;
use frontend\models\LoginForm;
use common\utils\GeetestLib;

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
        if (Yii::$app->request->post()) {
            dprint($_POST);
//            if ($_GET['type'] == 'pc') {
//                $GtSdk = new GeetestLib(Yii::$app->params['geetest_info']['captcha_id'], Yii::$app->params['geetest_info']['private_key']);
//            } elseif ($_GET['type'] == 'mobile') {
//                $GtSdk = new GeetestLib(Yii::$app->params['geetest_info']['mobile_captcha_id'], Yii::$app->params['geetest_info']['mobile_private_key']);
//            }
//
//            pprint($GtSdk);

//            $user_id = $_SESSION['geetest_user_id'];
//            if ($_SESSION['geetest_gtserver'] == 1) {   //服务器正常
//                $result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $user_id);
//                if ($result) {
//                    echo '{"status":"success"}';
//                } else {
//                    echo '{"status":"fail"}';
//                }
//            } else {  //服务器宕机,走failback模式
//                if ($GtSdk->fail_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'])) {
//                    echo '{"status":"success"}';
//                } else {
//                    echo '{"status":"fail"}';
//                }
//            }
        }
//        if ($model->load(Yii::$app->request->post())) {
//            if ($user = $model->signup()) {
//                if (Yii::$app->getUser()->login($user)) {
//                    return $this->goHome();
//                }
//            }
//        }
        else {
            return $this->render('signup');
        }
    }

    /**
     * 登录
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                    'model' => $model,
            ]);
        }
    }

    public function actionStart() {
        if ($_GET['type'] == 'pc') {
            $GtSdk = new GeetestLib(Yii::$app->params['geetest_info']['captcha_id'], Yii::$app->params['geetest_info']['private_key']);
        } elseif ($_GET['type'] == 'mobile') {
            $GtSdk = new GeetestLib(Yii::$app->params['geetest_info']['mobile_captcha_id'], Yii::$app->params['geetest_info']['mobile_private_key']);
        }
//        session_start();
        $user_id = "geetest_test";
        $status = $GtSdk->pre_process($user_id);
        $_SESSION['geetest_gtserver'] = $status;
        $_SESSION['geetest_user_id'] = $user_id;
        echo $GtSdk->get_response_str();
    }

}
