<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<link href="<?php echo $themeUrl; ?>/libs/autovalidate/style.css" rel="stylesheet">
<div class="container block_box">
    <div class="text-right" style="float: right;"><a href="/"><i class="glyphicon glyphicon-home"></i> 网站首页</a> &nbsp;&nbsp; 已有账号，<a href="<?php echo Url::to(['/users/login']); ?>">请登录</a></div>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="javascript:void(0)">新用户注册</a></li>
    </ul>
    <div class="user_table_box">
        <div class="user_reg_box">
            <?php $form = ActiveForm::begin(['id' => 'user_reg_form','class' => 'user_register']); ?>
                <input class="" name="type" type="hidden" value="pc">
                <table class="table">
                    <tr>
                        <th class="text-right" style="width: 100px;">手机号</th>
                        <td><input class="form-control" type="text" name='mobile' value="<?php echo isset($username) ? $username : ''; ?>" pattern="mobi" alt="填写正确的手机格式" /><label>填写正确的手机格式</label></td>
                    </tr>
                    <tr>
                        <th class="text-right">登录密码</th>
                        <td><input class="form-control" type="password" name='password' pattern="^\S{6,32}$" bind='repassword' alt='填写6-32个字符' /><label>填写登录密码，6-32个字符</label></td>
                    </tr>
                    <tr>
                        <th class="text-right">确认密码</td>
                        <td><input class="form-control" type="password" name='repassword' pattern="^\S{6,32}$" bind='password' alt='重复上面所填写的密码' /><label>重复上面所填写的密码</label></td>
                    </tr>
                    <tr>
                        <th class="text-right"></th>
                        <td>
                            <div id="embed-captcha"></div>
                            <p id="wait" class="show">正在加载验证码......</p>
                            <p id="notice" class="hide">请先拖动验证码到相应位置</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <input type="checkbox" name="agreen" checked="true"/>
                            我已阅读并同意
                            <a id="protocol" href="javascript:;">《用户注册协议》</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <input type="submit" id="embed-submit" class="btn btn-lg btn-default" value="立即注册"/>
                        </td>
                    </tr>
                </table>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="reg_ad">
            <img src="<?php echo Yii::$app->params['upload_url'];?>/upload/reg_ad.jpg"/>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/autovalidate/validate.js"></script>
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
    $("input[name=mobile]").blur(function(){
        var mobile = $("input[name=mobile]").val();
        $.post('<?php echo Yii::$app->urlManager->createUrl('/users/check-name');?>',{mobile:mobile},function(data){
            if(1===data.errcode){
                layer.msg(data.errmsg, {icon: 2, time: 2000});
            }
        },'json');
    });
</script>
<script src="http://code.jquery.com/jquery-1.12.3.min.js"></script>
<script src="http://static.geetest.com/static/tools/gt.js"></script>
<script>
    var handlerEmbed = function (captchaObj) {
        $("#embed-submit").click(function (e) {
            var validate = captchaObj.getValidate();
            if (!validate) {
                $("#notice")[0].className = "show";
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
                product: "embed", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                // 更多配置参数请参见：http://www.geetest.com/install/sections/idx-client-sdk.html#config
            }, handlerEmbed);
        }
    });
</script>
