<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace weibo\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\filters\AccessControl;
use weiboCommon\models\Weibo;

/**
 * Description of SiteController
 *
 * @author baihua <baihua_2011@163.com>
 */
class SiteController extends BaseController {

    public function actionIndex() {
        /**
         * SELECT weibo.id AS id,weibo.content AS content,weibo.isturn AS isturn,weibo.time AS time,
         * weibo.turn AS turn,weibo.keep AS keep,weibo.comment AS comment,weibo.uid AS uid,userinfo.username AS username,
         * userinfo.face50 AS face,picture.mini AS mini,picture.medium AS medium,picture.max AS max
         * FROM hd_weibo weibo
         * LEFT JOIN hd_userinfo userinfo ON weibo.uid = userinfo.uid
         * LEFT JOIN hd_picture picture ON weibo.id = picture.wid
         * WHERE ( weibo.uid IN (1) ) ORDER BY weibo.time DESC LIMIT 0,20
         */
        $data = Weibo::find()->where('uid in (1)');
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '20']);
        $query = new \yii\db\Query;
        $query->select(['weibo.id', 'weibo.content', 'weibo.isturn', 'weibo.time', 'weibo.turn', 'weibo.keep', 'weibo.comment', 'weibo.uid', 'userinfo.username', 'userinfo.face50 as face', 'picture.mini', 'picture.medium', 'picture.max'])
            ->distinct()
            ->from('{{%weibo}} as weibo')
            ->leftJoin('{{%userinfo}} as userinfo', 'weibo.uid = userinfo.uid')
            ->leftJoin('{{%picture}} as picture', 'weibo.id = picture.wid')
            ->where('weibo.uid in (1)')
            ->orderBy('weibo.time')
            ->offset($pages->offset)
            ->limit($pages->limit);
//            ->limit(20);
        $command = $query->createCommand();
        $weiboInfo = $command->queryAll();
        return $this->render('index', [
                'weiboInfo' => $weiboInfo,
                'pages' => $pages,
        ]);
    }
    
    
     /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

}
