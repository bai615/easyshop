<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * 用户信息表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Member extends ActiveRecord {

    /**
     * 更新账号金额
     * @param type $finnalAmount
     * @param type $userId
     * @return type
     */
    public function updateBalance($finnalAmount, $userId) {
        $memberModel = new Member();
        $memberInfo = $memberModel->find()
            ->select(['user_id', 'balance'])
            ->where('user_id=:userId', [':userId' => $userId])
            ->one();
        $memberInfo->balance = $finnalAmount;
        return $memberInfo->update();
    }

    public function getUsers() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
