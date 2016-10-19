<?php

$params = array_merge(
    require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);   


return [
    'id' => 'app-link',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'link\controllers',
    'components' => [
        'cache' => [  
            'class' => 'yii\caching\FileCache',  
        ],  
        'link' => [
            'class' => 'link\components\linkComponent', //加载类库
        ],
        'view' => [
            'theme' => [
                'basePath' => '@link/themes/basic',
                'baseUrl' => '@web/themes/basic',
                'pathMap' => [
                    '@link/views' => '@link/themes/basic',
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
//                    'sourcePath' => null,
//                    'js' => []
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
            ],
        ],
    ],
    'params' => $params,
];


