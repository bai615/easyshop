<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';
    public $basePath = '@webroot';  
    public $baseUrl = '@web/themes/quirk';  
    public $css = [
        'css/font-awesome/css/font-awesome.css',
        'css/quirk.css',
        'css/site.css',
    ];
    public $js = [
        'js/quirk.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
