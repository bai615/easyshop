<?php

namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Admin Login Form
 * 
 * @author baihua <baihua_2011@163.com>
 */
class LoginForm extends Model {

    public $adminname;
    public $password;
    public $rememberMe = true;
//    public $captcha;
//    public $verifyCode;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // adminname and password are both required
            [['adminname', 'password'], 'required', 'message' => '不能为空'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
//            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels() {
        return [
            'adminname' => '用户名',
            'password' => '密码',
            'rememberMe' => '自动登录',
//            'captcha' => '验证码',
//            'verifyCode' => '', //验证码的名称，根据个人喜好设定 
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect adminname or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided adminname and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[adminname]]
     *
     * @return User|null
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = Admin::findByAdminName($this->adminname);
        }

        return $this->_user;
    }

}
