<?php

namespace frontend\logics;

use Yii;
use common\utils\GeetestLib;
use common\models\User;
use common\utils\CommonTools;

/**
 * 用户逻辑
 *
 * @author baihua <baihua_2011@163.com>
 */
class UserLogic {

    /**
     * 注册处理
     * @param type $data
     * @return type
     */
    public function signUpAct($data) {
        //校验验证码
        $result = $this->checkVerify($data);
        if (1 == $result['errcode']) {
            return $result['errmsg'];
        }
        //验证用户输入信息
        $checkInfo = $this->checkRegData($data);
        if (1 == $checkInfo['errcode']) {
            return $checkInfo['errmsg'];
        }
        //验证通过，进行信息注册
        $signUpInfo = $this->signUp($data);
        if (1 == $signUpInfo['errcode']) {
            return $signUpInfo['errmsg'];
        }
        return 'OK';
    }

    /**
     * 校验验证码
     * @param type $data
     * @return type
     */
    public function checkVerify($data) {
        if ($data['type'] == 'pc') {
            $GtSdk = new GeetestLib(Yii::$app->params['geetest_info']['captcha_id'], Yii::$app->params['geetest_info']['private_key']);
        } elseif ($data['type'] == 'mobile') {
            $GtSdk = new GeetestLib(Yii::$app->params['geetest_info']['mobile_captcha_id'], Yii::$app->params['geetest_info']['mobile_private_key']);
        }
        $user_id = Yii::$app->session['geetest_user_id'];
        if (Yii::$app->session['geetest_gtserver'] == 1) {   //服务器正常
            $result = $GtSdk->success_validate($data['geetest_challenge'], $data['geetest_validate'], $data['geetest_seccode'], $user_id);
            if ($result) {
                return array('errcode' => 0, 'errmsg' => 'OK');
            } else {
                return array('errcode' => 1, 'errmsg' => '验证码错误');
            }
        } else {  //服务器宕机,走failback模式
            if ($GtSdk->fail_validate($data['geetest_challenge'], $data['geetest_validate'], $data['geetest_seccode'])) {
                return array('errcode' => 0, 'errmsg' => 'OK');
            } else {
                return array('errcode' => 1, 'errmsg' => '验证码错误');
            }
        }
    }

    /**
     * 验证注册信息
     * @param type $data
     * @return type
     */
    public function checkRegData($data) {
        //验证是否选择注册协议
        if (empty($data['agreen'])) {
            return array('errcode' => 1, 'errmsg' => '请选择同意注册协议');
        }
        //验证必填项
        if (empty($data['username']) || empty($data['password']) || empty($data['repassword'])) {
            return array('errcode' => 1, 'errmsg' => '信息不完整，请完善信息');
        }
        //验证手机号格式
        if (preg_match('/^1[3587]\d{9}$/', $data['username']) == 0) {
            return array('errcode' => 1, 'errmsg' => '请填写正确的手机号');
        }
        //验证密码长度
        $pwdLen = iconv_strlen($data['password']);
        if (6 > $pwdLen || 32 < $pwdLen) {
            return array('errcode' => 1, 'errmsg' => '请填写密码6-16位');
        }
        //验证两次密码是否一致
        if ($data['password'] != $data['repassword']) {
            return array('errcode' => 1, 'errmsg' => '两次密码不一致');
        }
        return array('errcode' => 0, 'errmsg' => 'OK');
    }

    /**
     * 注册
     * @param type $data
     * @return type
     */
    public function signUp($data) {
        if ($data) {
            $salt = uniqid();
            $userModel = new User();
            //查询用户信息
            $userInfo = $userModel->find()
                ->select(['username'])
                ->where('username=:username and is_del=0', [':username' => $data['username']])
                ->one();
            if ($userInfo) {
                return array('errcode' => 1, 'errmsg' => '您的手机已被占用');
            } else {
                //进行信息注册
                $userModel->username = $data['username'];
                $userModel->password = CommonTools::getPwd($data['password'], $salt);
                $userModel->salt = $salt;
                $userModel->created_time = date('Y-m-d H:i:s');
                $userModel->save();
                return array('errcode' => 0, 'errmsg' => 'OK');
            }
        }
        return array('errcode' => 1, 'errmsg' => '注册失败，请稍后重试');
    }

    /**
     * 通过名称检查用户是否存在
     * @param type $username
     * @return type
     */
    public static function checkByName($username) {
        $userModel = new User();
        //查询用户信息
        $userInfo = $userModel->find()
            ->select(['id'])
            ->where('username=:username and is_del=0', [':username' => $username])
            ->one();
        //判断用户是否存在
        return empty($userInfo) ? false : true;
    }

    /**
     * 登录
     * @param type $userInfo
     * @param type $online
     * @return boolean
     */
    public static function login($userInfo, $online) {
        //保存session信息
        Yii::$app->session['shopUserInfo'] = array(
            'userId' => $userInfo['id'],
            'userName' => $userInfo['username'],
            'head_ico' => $userInfo['head_ico'],
        );
        if ($online) {
            //保存自动登录信息
            self::saveAutoLogin($userInfo['username']);
        }
        //更新最后登录时间和IP
        $userInfo->last_login_time=  date('Y-m-d H:i:s');
        $userInfo->last_login_ip = CommonTools::real_ip();
        $userInfo->update();
        return true;
    }

    /**
     * 保存自动登录信息
     * @param type $username
     */
    private static function saveAutoLogin($username) {
        $ip = CommonTools::real_ip();
        //自动登录设置
        $value = $username . '|' . $ip;
        $value = CommonTools::encrypt($value, 'E');
        $cookieName = CommonTools::getAutoCookieName();
        // 从 "request"组件中获取cookie集合(yii\web\CookieCollection)
        $cookies = Yii::$app->response->cookies;

        // 添加一个新的cookie
        $cookies->add(new \yii\web\Cookie([
            'name' => $cookieName,
            'value' => $value,
            'expire' => Yii::$app->params['auto_login_time'], //有限期
        ]));
    }

    /**
     * 退出登录
     */
    public static function logout() {
        // 从"response"组件中获取cookie 集合(yii\web\CookieCollection)
        $cookies = Yii::$app->response->cookies;
        $cookieName = CommonTools::getAutoCookieName();
        // 删除一个cookie
        $cookies->remove($cookieName);
        $session = Yii::$app->session;
        // 删除一个session变量
        $session->remove('shopUserInfo');
    }

}
