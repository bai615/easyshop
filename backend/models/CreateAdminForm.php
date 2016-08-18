<?php

namespace backend\models;

use yii\base\Model;

/**
 * Create Admin Form
 *
 * @author baihua <baihua_2011@163.com>
 */
class CreateAdminForm extends Model {

    public $adminname;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['adminname', 'filter', 'filter' => 'trim'],
            ['adminname', 'required'],
            ['adminname', 'unique', 'targetClass' => '\backend\models\Admin', 'message' => 'This username has already been taken.'],
            ['adminname', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\Admin', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
    /**
     * create admin
     *
     * @return User|null the saved model or null if saving fails
     */
    public function createAdmin() {
        if (!$this->validate()) {
            return null;
        }
        $admin = new Admin();
        $admin->adminname = $this->adminname;
        $admin->email = $this->email;
        $admin->setPassword($this->password);
        $admin->generateAuthKey();

        return $admin->save() ? $admin : null;
    }

}
