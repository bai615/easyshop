
<li class="active">会员管理</li>
<li class="active">编辑会员</li>
</ol>                
<hr class="darken"> 

<?php

use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>

<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/autovalidate/validate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/form.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $themeUrl; ?>/libs/autovalidate/style.css" />
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate-plugin.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/area_select.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/layer/layer.js"></script> 

<div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading nopaddingbottom">
                <h4 class="panel-title">会员信息</h4>
            </div>
            <div class="panel-body">
                <hr>
                <?php $form = ActiveForm::begin(['id' => 'basicForm', 'options' => ['name' => 'userForm', 'class' => 'form-horizontal'], 'action' => Url::to(['/users/save'])]); ?>
                <input type="hidden" name="id" value="" />
                <input type='hidden' name="callback" value="" />
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger">*</span> 手机号：</label>
                    <div class="col-sm-9">
                        <input type="text" name="mobile" class="form-control" pattern="mobi" alt="填写正确的手机格式" />
                        注：手机号即为会员登录用户名
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger">*</span> 电子邮箱：</label>
                    <div class="col-sm-9">
                        <input type="text" name="email" class="form-control" alt="填写正确的邮箱格式" pattern="email" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger">*</span> 登录密码：</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name='password' pattern="^\S{6,32}$" alt='填写6-32个字符' /><label>填写登录密码，6-32个字符</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 会员组：</label>
                    <div class="col-sm-2">
                        <select class="form-control" name="group_id" onchange="create_attr(this.value)">
                            <option value="0">请选择 </option>
                            <?php
                            $groupList = common\models\UserGroup::getAll();
                            if ($groupList):
                                foreach ($groupList as $value):
                                    ?>
                                    <option value="<?= $value['id']; ?>"><?= $value['group_name']; ?></option>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 真实姓名：</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name='true_name' /><label>填写真实姓名</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 性别：</label>
                    <div class="col-sm-9">
                        <label class="checkbox-inline">
                            <input type="radio" name="sex" id="sex-1" value="1" checked="checked"/>男
                        </label>
                        <label class="checkbox-inline">
                            <input type="radio" name="sex" id="sex-2" value="2" />女
                        </label> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 出生日期：</label>
                    <div class="col-sm-2">
                        <input type="text" style="height: 34px;" value="" name="birthday" class="Wdate form-control" onFocus="WdatePicker({isShowClear: false, readOnly: true, maxDate: '%y-%M-%d'})" >
                        <label> 填写正确的日期格式</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 所在地区：</label>
                    <div class="col-sm-2">
                        <select class="form-control" name="province" child="city,area" onchange="areaChangeCallback(this);"></select>
                        <label> 填写正确的区域地址</label><br/>
                        <select class="form-control" name="city" child="area" parent="province" onchange="areaChangeCallback(this);"></select><br/>
                        <select class="form-control" name="area" parent="city"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 联系地址：</label>
                    <div class="col-sm-9">
                        <input type="text" name="contact_addr" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> QQ号码：</label>
                    <div class="col-sm-9">
                        <input type="text" name="qq" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 状态：</label>
                    <div class="col-sm-2">
                        <select class="form-control" name="status">
                            <option value="1">正常</option>
                            <option value="2">删除</option>
                            <option value="3">锁定</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button class="btn btn-success btn-quirk btn-wide mr5">保存</button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php if (isset($userData)): ?>
    <script type="text/javascript">
        $(function ()
        {
            var formObj = new Form('userForm');
            var data = <?php echo Json::encode(ArrayHelper::toArray($userData)); ?>;
            formObj.init(data);
        });
    </script>
<?php endif; ?>    
<script type='text/javascript'>
//DOM加载完毕
    $(function () {
        //初始化地域联动
        template.compile("areaTemplate", areaTemplate);
<?php
if (isset($userData['area']) && $userData['area']) {
    $area = explode(',', trim($userData['area'], ','));
}
?>

<?php
if (isset($area[0]) && isset($area[1]) && isset($area[2])):
    ?>
            createAreaSelect('province', 0,<?= $area[0] ?>);
            createAreaSelect('city',<?= $area[0] ?>,<?= $area[1] ?>);
            createAreaSelect('area',<?= $area[1] ?>,<?= $area[2] ?>);
    <?php
else:
    ?>
            createAreaSelect('province', 0, "");
<?php
endif;
?>
    });
    /**
     * 生成地域js联动下拉框
     * @param name
     * @param parent_id
     * @param select_id
     */
    function createAreaSelect(name, parent_id, select_id)
    {
        //生成地区
        $.getJSON("<?php echo Url::to(['/common/area-child']); ?>", {"aid": parent_id, "random": Math.random()}, function (json)
        {
            $('[name="' + name + '"]').html(template.render('areaTemplate', {"select_id": select_id, "data": json}));
        });
    }

    //校验用户名是否已被占用
    $("input[name=mobile]").blur(function () {
        var id = $("input[name=id]").val();
        var mobile = $("input[name=mobile]").val();
        $.post('<?php echo Url::to(['/users/check-name']); ?>', {id: id, mobile: mobile}, function (data) {
            if (1 === data.errcode) {
                layer.msg(data.errmsg, {icon: 2, time: 2000});
            }
        }, 'json');
    });
    //校验邮箱是否已被占用
    $("input[name=email]").blur(function () {
        var id = $("input[name=id]").val();
        var email = $("input[name=email]").val();
        $.post('<?php echo Url::to(['/users/check-email']); ?>', {id: id, email: email}, function (data) {
            if (1 === data.errcode) {
                layer.msg(data.errmsg, {icon: 2, time: 2000});
            }
        }, 'json');
    });
</script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/my97date/wdatepicker.js"></script>