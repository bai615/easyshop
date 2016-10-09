<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\helpers\Json;
use common\models\User;
use common\models\UserGroup;

/**
 * Description of UsersController
 *
 * @author baihua <baihua_2011@163.com>
 */
class UsersController extends BaseController {

    /**
     * 会员列表
     * @return type
     */
    public function actionList() {
        $this->getBaseData();
        $data = User::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $model = $data->select(['id', 'username', 'created_time', 'is_del'])
            ->where('is_del != 1')
            ->orderBy('id desc')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('list', [
                'model' => $model,
                'pages' => $pages,
        ]);
    }

    /**
     * 会员组列表
     * @return type
     */
    public function actionGroup() {
        $this->getBaseData();
        $data = UserGroup::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '10']);
        $model = $data->select(['id', 'group_name', 'discount', 'created_time', 'is_del'])
            ->where('is_del != 1')
            ->orderBy('id desc')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('group', [
                'model' => $model,
                'pages' => $pages,
        ]);
    }

    /**
     * 添加用户组
     * @return type
     */
    public function actionCreateGroup() {
        $this->getBaseData('users', 'group');
        return $this->render('editGroup');
    }

    /**
     * 保存用户组信息
     */
    public function actionSaveGroup() {
        $groupId = intval(Yii::$app->request->post('id'));
        $discount = intval(Yii::$app->request->post('discount'));
        $groupName = Yii::$app->request->post('group_name');
        $groupData = array(
            'discount' => $discount,
            'group_name' => $groupName,
        );

        if ($discount > 100) {
            $url = Url::to(['/users/group']);
            $this->redirect(Url::to(['/common/message', 'message' => '折扣率不能大于100', 'url' => $url]));
        }

        $model = new UserGroup();
        if ($groupId) {
            //保存信息
            $model->updateAll($groupData, 'id=:groupId', [':groupId' => $groupId]);
        } else {
            //添加新品牌
            foreach ($groupData as $key => $value) {
                $model->$key = $value;
            }
            $model->created_time = date('Y-m-d H:i:s');
            $model->save();
        }
        $this->redirect(Url::to(['/users/group']));
    }

    /**
     * 编辑用户组
     * @return type
     */
    public function actionEditGroup() {
        $this->getBaseData('users', 'group');
        $groupId = intval(Yii::$app->request->get('id'));
        $groupInfo = array();
        if ($groupId) {
            $groupModel = new UserGroup();
            $groupInfo = $groupModel->find()->where('id=:groupId', [':groupId' => $groupId])->one();
        }
        return $this->render('editGroup', ['groupInfo' => $groupInfo]);
    }

    /**
     * 批量删除用户组
     */
    public function actionDelGroup() {
        $groupId = (Yii::$app->request->post('ids'));
        $resultArr = ['errcode' => 1, 'errmsg' => '删除失败'];
        if ($groupId) {
            $model = new UserGroup();
            if (is_array($groupId)) {
                $where = "id in (" . join(',', $groupId) . ")";
            } else {
                $where = 'id = ' . $groupId;
            }
            $result = $model->updateAll(['is_del' => 1], $where);
            if ($result) {
                $resultArr = ['errcode' => 0, 'errmsg' => '删除成功'];
            }
        }
        echo Json::encode($resultArr);
    }

}
