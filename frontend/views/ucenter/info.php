<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<link href="<?php echo $themeUrl; ?>/libs/autovalidate/style.css" rel="stylesheet">
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
            <h4>个人资料</h4>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">会员信息</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="text-right" style="width:100px;">登录用户名：</th>
                                <td><?php echo $userInfo['username']; ?></td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">个人信息</h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'info-form', 'options' => ['novalidate' => 'true'], 'action' => Url::to(['/ucenter/info'])]); ?>
                    <table class="table table-bordered ucenter_user_info">
                        <tbody>
                            <tr>
                                <th class="text-right" style="width:100px;">真实姓名：</th>
                                <td>
                                    <input type="text" alt="填写真实的姓名" pattern="required" value="<?php echo $memberInfo['true_name']; ?>" name="true_name" class="form-control">
                                    <label> 填写真实的姓名</label>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">性　别：</th>
                                <td>
                                    <label class="checkbox-inline">
                                        <input type="radio" name="sex" id="sex-1" value="1" <?php if(1==$memberInfo['sex'])echo 'checked';?>>男
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="radio" name="sex" id="sex-2" value="2" <?php if(2==$memberInfo['sex'])echo 'checked';?>>女
                                    </label> 
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">出生日期：</th>
                                <td>
                                    <input type="text" alt="填写正确的日期格式" pattern="date" style="height: 34px;" value="<?php echo $memberInfo['birthday']; ?>" name="birthday" class="Wdate form-control" onFocus="WdatePicker({isShowClear: false, readOnly: true})" >
                                    <label> 填写正确的日期格式</label>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">所在地区：</th>
                                <td>
                                    <select class="form-control" name="province" child="city,area" onchange="areaChangeCallback(this);"></select>
                                    <label> 填写正确的区域地址</label><br/>
                                    <select class="form-control" name="city" child="area" parent="province" onchange="areaChangeCallback(this);"></select><br/>
                                    <select class="form-control" name="area" parent="city"></select>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">联系地址：</th>
                                <td>
                                    <input type="text" alt="填写正确的联系地址" pattern="required" value="<?php echo $memberInfo['contact_addr']; ?>" name="contact_addr" class="form-control">
                                    <label> 填写正确的联系地址</label>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">联系方式：</th>
                                <td>
                                    <input type="text" alt="填写正确的手机格式" pattern="mobi" value="<?php echo $memberInfo['mobile']; ?>" name="mobile" class="form-control">
                                    <label> 填写正确的手机格式</label>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">电子邮箱：</th>
                                <td>
                                    <input type="text" alt="填写正确的邮箱格式" pattern="email" value="<?php echo $memberInfo['email']; ?>" name="email" class="form-control">
                                    <label> 填写正确的邮箱格式</label>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">QQ号码：</th>
                                <td>
                                    <input type="text" alt="填写正确的QQ号码" pattern="qq" value="<?php echo $memberInfo['qq']; ?>" name="qq" class="form-control">
                                    <label> 填写正确的QQ号码</label>
                                </td>
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
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate-plugin.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/area_select.js"></script>
<script type='text/javascript'>
//DOM加载完毕
$(function(){
	//初始化地域联动
	template.compile("areaTemplate",areaTemplate);
<?php
if(isset($memberInfo['area']) && $memberInfo['area']){
    $area = explode(',',trim($memberInfo['area'],','));
}
?>

	<?php
    if(isset($area[0]) && isset($area[1]) && isset($area[2])):
        ?>
        createAreaSelect('province',0,<?=$area[0]?>);
        createAreaSelect('city',<?=$area[0]?>,<?=$area[1]?>);
        createAreaSelect('area',<?=$area[1]?>,<?=$area[2]?>);
    <?php
    else:
    ?>
       createAreaSelect('province',0,"");
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
function createAreaSelect(name,parent_id,select_id)
{
	//生成地区
	$.getJSON("<?php echo Url::to(['/common/area-child']);?>",{"aid":parent_id,"random":Math.random()},function(json)
	{
		$('[name="'+name+'"]').html(template.render('areaTemplate',{"select_id":select_id,"data":json}));
	});
}
</script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/layer/layer.js"></script> 
<script type="text/javascript">
<?php
if (isset($resultArr['errcode'])):
    ?>
        layer.ready(function () {
            <?php
            if(0==$resultArr['errcode']):
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
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/my97date/wdatepicker.js"></script>