<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="container block_box">
    <div class="user_login_box">
        <div class="login_ad">
            <img src="<?php echo Yii::$app->params['upload_url'];?>/upload/login_ad.png" alt="" title="" style="height: 400px;" />
        </div>
        <div class="login_form">
            <h2>欢迎登录</h2>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <input class="" name="type" type="hidden" value="pc">
            <table>
                <tr>
                    <td class="text-right">
                        <a href="<?php echo Url::to(['/users/signup']); ?>">立即注册»</a>
                    </td>
                </tr>
                <tr>
                    <td class="username">
                        <i></i>
                        <?= $form->field($model, 'username')->textInput(['id' => 'user_login', 'placeholder' => '手机号', 'class' => '']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="password">
                        <i></i>
                        <?= $form->field($model, 'password')->passwordInput(['id' => 'user_pass', 'placeholder' => '密码', 'class' => '']) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span style="float: left;">
                            <?= $form->field($model, 'online')->checkbox(['checked' => true]) ?>
                        </span>
                        <span class="find_pwd"><a href="">忘记密码</a></span>
                    </td>
                </tr>
                <tr>
                    <td class="has-error">
                        <div id="embed-captcha"></div>
                        <p id="wait" class="show">正在加载验证码......</p>
                        <p id="notice" class="hide">请先拖动验证码到相应位置</p>
                    </td>
                </tr>
                <tr>
                    <td class="text-center"><button type="submit" id="embed-submit" class="btn btn-lg btn-danger"> 登&nbsp;录 </button></td>
                </tr>
                <tr style="display: none;">
                    <td>
                        <div class="other_login">
                            <p>使用合作网站登录</p>
                            <dl>
                                <dt><a href="javascript:void(0)" title="QQ" class="qq">QQ</a></dt>
                                <dt><a href="javascript:void(0)" title="微信" class="wx">微信</a></dt>
                                <dt><a href="javascript:void(0)" title="新浪微博" class="wb">新浪微博</a></dt>
                                <dt><a href="javascript:void(0)" title="支付宝" class="alpay">支付宝</a></dt>
                            </dl>
                        </div>
                    </td>
                </tr>
            </table>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script src="http://code.jquery.com/jquery-1.12.3.min.js"></script>
<script src="http://static.geetest.com/static/tools/gt.js"></script>
<script>
    var handlerEmbed = function (captchaObj) {
        $("#embed-submit").click(function (e) {
            var validate = captchaObj.getValidate();
            if (!validate) {
                $("#notice")[0].className = "show help-block help-block-error";
                setTimeout(function () {
                    $("#notice")[0].className = "hide";
                }, 2000);
                e.preventDefault();
            }
        });
        // 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
        captchaObj.appendTo("#embed-captcha");
        captchaObj.onReady(function () {
            $("#wait")[0].className = "hide";
        });
        console.log(captchaObj);
        return false;
    };
    $.ajax({
        // 获取id，challenge，success（是否启用failback）
        url: "<?php echo Url::to(['/users/start']);?>?type=pc&t=" + (new Date()).getTime(), // 加随机数防止缓存
        type: "get",
        dataType: "json",
        success: function (data) {
            // 使用initGeetest接口
            // 参数1：配置参数
            // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
            initGeetest({
                gt: data.gt,
                challenge: data.challenge,
                product: "float", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                // 更多配置参数请参见：http://www.geetest.com/install/sections/idx-client-sdk.html#config
            }, handlerEmbed);
        }
    });
</script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/layer/layer.js"></script> 
<script type="text/javascript">
<?php
if (isset($errmsg)):
    ?>
        layer.ready(function () {
            layer.msg('<?php echo $errmsg; ?>', {icon: 2, time: 2000});
        });
    <?php
endif;
?>
</script>
