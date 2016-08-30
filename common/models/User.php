<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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


}
