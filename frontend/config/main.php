<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'goods' => [
            'class' => 'frontend\components\goodsComponent', //加载类库
        ],
        'view' => [
            'theme' => [
                'basePath' => '@frontend/themes/basic',
                'baseUrl' => '@web/themes/basic',
                'pathMap' => [
                    '@frontend/views' => '@frontend/themes/basic',
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [//禁用默认引入的jquery
                    'sourcePath' => null,
                    'js' => []
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true, //对url进行美化
            'showScriptName' => false, //隐藏index.php
            'suffix' => '.html', //后缀
            'rules' => [
                //首页
                'index' => 'site/index',
                //登录页
                'login' => 'users/login',
                //退出页
                'logout' => 'users/logout',
                //注册页
                'reg' => 'users/signup',
                //详情页
                'item_<id:\d+>' => 'site/products',
                //成功信息页
                'success' => 'common/success',
                //404
                'error' => 'common/error',
                //警告
                'warning' => 'common/warning',
                //支付同步信息返回页
                'callback' => 'pay/callback',
                //支付异步信息通知页
                'notify' => 'pay/notify',
                //我的订单页
                'myOrder' => 'ucenter/order',
                //订单详情页
                'detail_<id:\d+>' => 'ucenter/orderDetail',
            ],
        ],
    ],
    'params' => $params,
];
