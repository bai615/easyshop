<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>

<div class="col-md-12">
    <div class="panel">
        <div class="panel-heading nopaddingbottom">
            <h4 class="panel-title">分类信息</h4>
        </div>
        <div class="panel-body">
            <hr>
            <?php $form = ActiveForm::begin(['id' => 'basicForm', 'options' => ['name' => 'categoryForm', 'class' => 'form-horizontal'], 'action' => Url::to(['/site/save-category'])]); ?>
            <input type="hidden" name="id" value="" />
            <input type='hidden' name="callback" value="" />
            <div class="form-group">
                <label class="col-sm-2 control-label"><span class="text-danger">*</span> 分类名称：</label>
                <div class="col-sm-9">
                    <input type="text" name="name" class="form-control" pattern="required" alt="分类名称不能为空" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">上级分类： <span class="text-danger"></span></label>
                <div class="col-sm-9">
                    <select class="form-control" name="parent_id">
                        <option value="0">顶级分类</option>
                        <?php
                        $categoryList = link\models\Category::getAll();
                        if ($categoryList):
                            foreach ($categoryList as $info):
                                ?>
                                <option value="<?= $info['id']; ?>"><?= $info['name']; ?></option>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">首页是否显示：</label>
                <div class="col-sm-8">
                    <label class="checkbox-inline"> 
                        <input type="radio" name="visibility" id="visibility_0" value="1"> 是
                    </label>
                    <label class="checkbox-inline"> 
                        <input type="radio" name="visibility" id="visibility_2" value="0" checked="checked"> 否
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><span class="text-danger"></span> 排序：</label>
                <div class="col-sm-9">
                    <input type="text" name="sort" class="form-control" alt="排序必须是一个数字" empty="" pattern="int" />
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
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/autovalidate/validate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/form.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $themeUrl; ?>/libs/autovalidate/style.css" />