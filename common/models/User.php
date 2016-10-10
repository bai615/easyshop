<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\utils\CommonTools;

/**
 * Description of User
 *
 * @author baihua <baihua_2011@163.com>
 */
class User extends ActiveRecord implements IdentityInterface {

    public function getAuthKey() {
        
    }

    public function getId() {
        
    }

    public function validateAuthKey($authKey) {
        
    }

    public static function findIdentity($id) {
        
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        
    }

    /**
     * Finds user by userName
     *
     * @param string $userName
     * @return static|null
     */
    public static function findByUserName($userName) {
        return static::findOne(['username' => $userName, 'is_del' => 0]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($user, $password) {
        $newPassword = CommonTools::getPwd($password, $user['salt']);
        return ($user['password'] == $newPassword) ? true : false;
    }

    /**
     * 通过用户编号获取用户名
     * @param type $userId
     * @return type
     */
    public static function getNameById($userId) {
        $userInfo = static::findOne(['id' => $userId]);
        return empty($userInfo) ? '' : $userInfo['username'];
    }

    public function getMembers() {
        return $this->hasOne(Member::className(), ['user_id' => 'id']);
    }

}
