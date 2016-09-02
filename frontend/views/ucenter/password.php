<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<link href="<?php echo $themeUrl; ?>/libs/autovalidate/style.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/my97date/wdatepicker.js"></script>
<div class="container block_box">
    <div class="breadcrumb"><span>您当前的位置：</span> <a href="/">首页</a> 》个人资料</div>
    <div class="ucenter_box">
        <div class="col-md-2 ucenter_menu">
            <div class="list-group">
                <?php
                if ($this->context->menuData):
                    foreach ($this->context->menuData as $key => $info):
                        ?>
                        <a href="<?php echo Url::to([$info['url']]); ?>" class="list-group-item <?php
                        if ($key == $this->context->currentMenu) {
                            echo 'active';
                        }
                        ?>"><?php echo $info['name']; ?></a>
                           <?php
                       endforeach;
                   endif;
                   ?>
                <!--
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="divider"></a>
                <a href="#" class="list-group-item">Link</a>
                -->
            </div>
        </div>
        <div class="col-md-10 ucenter_main">
            <h4>密码管理</h4>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">修改密码</h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'password-form', 'options' => ['novalidate' => 'true'], 'action' => Url::to(['/ucenter/password'])]); ?>
                    <table class="table table-bordered ucenter_user_info">
                        <tbody>
                            <tr>
                                <th class="text-right" style="width:100px;">原密码：</th>
                                <td>
                                    <input type="password" alt="填写6-32个字符" pattern="^\S{6,32}$" value="" name="old_pwd" class="form-control">
                                    <label> 填写原登录密码，6-32个字符</label>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">新密码：</th>
                                <td><input class="form-control" type="password" name='password' pattern="^\S{6,32}$" bind='repassword' alt='填写6-32个字符' />
                                    <label> 填写新登录密码，6-32个字符</label></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">确认密码：</td>
                                <td><input class="form-control" type="password" name='repassword' pattern="^\S{6,32}$" bind='password' alt='重复上面所填写的密码' />
                                    <label> 重复上面所填写的密码</label></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td>
                                    <button class="btn btn-lg btn-danger" id="embed-submit" type="submit"><i class="glyphicon glyphicon-floppy-disk"></i> 保 存</button>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/autovalidate/validate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/layer/layer.js"></script> 
<script type="text/javascript">
<?php
if (isset($resultArr['errcode'])):
    ?>
        layer.ready(function () {
    <?php
    if (0 == $resultArr['errcode']):
        ?>
                layer.msg('<?php echo $resultArr['errmsg']; ?>', {icon: 1, time: 2000});
        <?php
    else:
        ?>
                layer.msg('<?php echo $resultArr['errmsg']; ?>', {icon: 2, time: 2000});
    <?php
    endif;
    ?>
        });
    <?php
endif;
?>
</script>    
