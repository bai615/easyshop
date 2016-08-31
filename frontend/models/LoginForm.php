<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\logics\UserLogic;

/**
 * User Login Form
 * 
 * @author baihua <baihua_2011@163.com>
 */
class LoginForm extends Model {

    public $username;
    public $password;
    public $online = true;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are required
            ['username', 'required', 'message' => '手机号必填'],
            ['password', 'required', 'message' => '密码必填'],
//            ['username', 'checkUserName'],
            // online must be a boolean value
            ['online', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
//            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels() {
        return [
            'username' => '',
            'password' => '',
            'online' => '自动登录',
//            'captcha' => '验证码',
//            'verifyCode' => '', //验证码的名称，根据个人喜好设定 
        ];
    }

    public function ckeckUserName($attribute, $params) {
        
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
            if (!$user || !$user->validatePassword($user, $this->password)) {
                $this->addError($attribute, '用户名或者密码错误.');
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
            return UserLogic::login($this->getUser(), $this->online);
//            return Yii::$app->user->login($this->getUser(), $this->online ? 3600 * 24 * 30 : 0);
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
            $this->_user = User::findByUserName($this->username);
        }

        return $this->_user;
    }

}
