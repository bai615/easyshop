<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Weibo';
$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <title><?= Html::encode($this->title) ?>-登录</title>
        <link rel="stylesheet" href="<?= $themeUrl ?>/css/login.css" />
        <script type="text/javascript" src='<?php echo $themeUrl; ?>/js/jquery-1.7.2.min.js'></script>
        <script type="text/javascript" src='<?php echo $themeUrl; ?>/js/jquery-validate.js'></script>
        <script type="text/javascript" src='<?php echo $themeUrl; ?>/js/login.js'></script>
    </head>
    <body>
        <div id='top-bg'></div>
        <div id='login-form'>
            <div id='login-wrap'>
                <p>还没有微博帐号？<a href='<?= Url::to(['/login/signup']) ?>'>立即注册</a></p>
                <?php $form = ActiveForm::begin(['id' => 'signin-form', 'action' => Url::to(['/login/signin']), 'method' => 'post', 'options' => ['name' => 'login']]); ?>
                <fieldset>
                    <legend>用户登录</legend>
                    <p>
                        <label for="account">登录账号：</label>
                        <input type="text" name='account' class='input'/>
                    </p>
                    <p>
                        <label for="pwd">密码：</label>
                        <input type="password" name='pwd' class='input'/>
                    </p>
                    <p>
                        <input type="checkbox" name='auto' checked='1' class='auto' id='auto'/>
                        <label for="auto">下次自动登录</label>
                    </p>
                    <p>
                        <input type="submit" value='马上登录' id='login'/>
                    </p>
                </fieldset>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </body>
</html>