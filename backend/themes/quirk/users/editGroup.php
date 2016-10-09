
<li class="active">会员管理</li>
<li class="active">编辑会员组</li>
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

<div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading nopaddingbottom">
                <h4 class="panel-title">用户组信息</h4>
            </div>
            <div class="panel-body">
                <hr>
                <?php $form = ActiveForm::begin(['id' => 'basicForm', 'options' => ['name' => 'groupForm', 'class' => 'form-horizontal'], 'action' => Url::to(['/users/save-group'])]); ?>
                <input type="hidden" name="id" value="" />
                <input type='hidden' name="callback" value="" />
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger">*</span> 用户组名称：</label>
                    <div class="col-sm-9">
                        <input type="text" name="group_name" class="form-control" pattern="required" alt="用户组名称不能为空" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 折扣率：</label>
                    <div class="col-sm-2">
                        <input type="text" name="discount" class="form-control" alt="折扣率必须是一个数字" empty="" pattern="int" />
                    </div>
                    <div class="col-sm-8">
                        %折扣率，例如：如果输入90，表示该会员组可以以商品原价的90%购买（不允许包含小数）。
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
<?php if (isset($groupInfo)): ?>
    <script type="text/javascript">
        $(function ()
        {
            var formObj = new Form('groupForm');
            var data = <?php echo Json::encode(ArrayHelper::toArray($groupInfo)); ?>;
            formObj.init(data);
        });
    </script>
<?php endif; ?>    