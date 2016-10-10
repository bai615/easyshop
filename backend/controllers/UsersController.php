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
use common\models\Member;
use common\utils\CommonTools;

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
     * 添加会员
     * @return type
     */
    public function actionCreate() {
        $this->getBaseData('users', 'list');
        return $this->render('edit');
    }

    /**
     * 保存会员信息
     */
    public function actionSave() {
        $userId = intval(Yii::$app->request->post('id'));
        $userData['username'] = Yii::$app->request->post('mobile');
        $password = Yii::$app->request->post('password');
        $status = intval(Yii::$app->request->post('status'));
        if ($password) {
            $salt = uniqid();
            $userData['salt'] = $salt;
            $userData['password'] = CommonTools::getPwd($password, $salt);
        }
        $userData['is_del'] = (2 == $status) ? 0 : $status;

        $memberData['email'] = Yii::$app->request->post('email');
        $memberData['true_name'] = Yii::$app->request->post('true_name');
        $memberData['mobile'] = Yii::$app->request->post('mobile');
        $province = intval(Yii::$app->request->post('province'));
        $city = intval(Yii::$app->request->post('city'));
        $area = intval(Yii::$app->request->post('area'));
        $memberData['area'] = ',' . $province . ',' . $city . ',' . $area . ',';
        $memberData['contact_addr'] = Yii::$app->request->post('contact_addr');
        $memberData['qq'] = Yii::$app->request->post('qq');
        $memberData['sex'] = intval(Yii::$app->request->post('sex'));
        $memberData['group_id'] = intval(Yii::$app->request->post('group_id'));
        $memberData['birthday'] = Yii::$app->request->post('birthday');
        $memberData['status'] = $status;

//        $url = Url::to(['/users/list']);

        $userModel = new User();
        //校验用户名
        $userInfo = $userModel->find()->where('username=:userName and is_del=0 and id != :userId', [':userName' => $userData['username'], ':userId' => $userId])->one();

        if ($userInfo) {
//            $this->redirect(Url::to(['/common/message', 'message' => '用户名重复', 'url' => $url]));
            die('<script>alert("用户名重复");window.history.go(-1);</script>');
        }
        $memberModel = new Member();
        //校验邮箱
        $memberInfo = $memberModel->find()->where('email=:email and user_id != :userId and status != 2', [':email' => $memberData['email'], ':userId' => $userId])->one();
        if ($memberInfo) {
//            $this->redirect(Url::to(['/common/message', 'message' => '邮箱重复', 'url' => $url]));
            die('<script>alert("邮箱重复");window.history.go(-1);</script>');
        }

        //添加新会员
        if (empty($userId)) {
            $createTime = date('Y-m-d H:i:s');
            //用户表数据
            foreach ($userData as $key => $value) {
                $userModel->$key = $value;
            }
            $userModel->created_time = $createTime;
            $userModel->save();
            $userId = $userModel->id;
            //用户信息表数据
            foreach ($memberData as $key => $value) {
                $memberModel->$key = $value;
            }
            $memberModel->user_id = $userId;
            $memberModel->time = $createTime;
            $memberModel->save();
        }
        //编辑会员
        else {
            //保存修改用户表数据
            $userModel->updateAll($userData, 'id=:userId', [':userId' => $userId]);
            //保存修改用户信息表数据
            $memberModel->updateAll($memberData, 'user_id=:userId', [':userId' => $userId]);
        }
        $this->redirect(Url::to(['/users/list']));
    }

    /**
     * 编辑会员
     * @return type
     */
    public function actionEdit() {
        $this->getBaseData('users', 'list');
        $userId = intval(Yii::$app->request->get('id'));
        $userData = array();
        $userModel = new User();
        //获取用户表信息
        $userInfo = $userModel->find()->where('id = :userId', [':userId' => $userId])->one();

        if ($userInfo) {
            $userData['id'] = $userInfo['id'];
            $userData['mobile'] = $userInfo['username'];
        }
        $memberModel = new Member();
        //获取用户信息表数据
        $memberInfo = $memberModel->find()->where('user_id = :userId', [':userId' => $userId])->one();
        if ($memberInfo) {
            $userData['email'] = $memberInfo['email'];
            $userData['group_id'] = $memberInfo['group_id'];
            $userData['true_name'] = $memberInfo['true_name'];
            $userData['sex'] = $memberInfo['sex'];
            $userData['birthday'] = $memberInfo['birthday'];
            $userData['area'] = $memberInfo['area'];
            $userData['contact_addr'] = $memberInfo['contact_addr'];
            $userData['qq'] = $memberInfo['qq'];
            $userData['status'] = $memberInfo['status'];
        }
        return $this->render('edit', ['userData' => $userData]);
    }

    /**
     * 检查用户是否已存在
     */
    public function actionCheckName() {
        $userId = Yii::$app->request->post('id');
        $username = Yii::$app->request->post('mobile');
        $userModel = new User();
        //查询用户信息
        $userInfo = $userModel->find()
            ->select(['id'])
            ->where('id != :userId and username=:username and is_del=0', [':userId' => $userId, ':username' => $username])
            ->one();
        if ($userInfo) {
            echo json_encode(['errcode' => 1, 'errmsg' => '您的手机已被占用']);
        } else {
            echo json_encode(['errcode' => 0, 'errmsg' => 'OK']);
        }
    }
    
    /**
     * 检查邮箱是否已存在
     */
    public function actionCheckEmail() {
        $userId = Yii::$app->request->post('id');
        $email = Yii::$app->request->post('email');
        $memberModel = new Member();
        //查询用户信息
        $userInfo = $memberModel->find()
            ->select(['user_id'])
            ->where('user_id != :userId and email=:email and status != 2', [':userId' => $userId, ':email' => $email])
            ->one();
        if ($userInfo) {
            echo json_encode(['errcode' => 1, 'errmsg' => '您的邮箱已被占用']);
        } else {
            echo json_encode(['errcode' => 0, 'errmsg' => 'OK']);
        }
    }

    /**
     * 批量删除用户
     */
    public function actionRemove() {
        $userId = (Yii::$app->request->post('ids'));
        $resultArr = ['errcode' => 1, 'errmsg' => '删除失败'];
        if ($userId) {
            $model = new User();
            if (is_array($userId)) {
                $where = "id in (" . join(',', $userId) . ")";
            } else {
                $where = 'id = ' . $userId;
            }
            $result = $model->updateAll(['is_del' => 1], $where);
            if ($result) {
                $resultArr = ['errcode' => 0, 'errmsg' => '删除成功'];
            }
        }
        echo Json::encode($resultArr);
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
//            $url = Url::to(['/users/group']);
//            $this->redirect(Url::to(['/common/message', 'message' => '折扣率不能大于100', 'url' => $url]));
            die('<script>alert("折扣率不能大于100");window.history.go(-1);</script>');
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
