<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
        ],
        'goods'=>[
            'class'=>'backend\components\goodsComponent',//加载类库
        ],
        'view' => [
            'theme' => [
                'basePath' => '@backend/themes/quirk',
                'baseUrl' => '@web/themes/quirk',
                'pathMap' => [
                    '@backend/views' => '@backend/themes/quirk',
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
//                'yii\bootstrap\BootstrapAsset' => [//禁用默认引入的bootstrap.css 文件
//                    'css' => []
//                ],
//                'yii\web\JqueryAsset' => [//禁用默认引入的jquery文件
//                    'sourcePath' => null,
//                    'js' => []
//                ],
            ],
        ],
    /*
      'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'rules' => [
      ],
      ],
     */
    ],
    'params' => $params,
];
