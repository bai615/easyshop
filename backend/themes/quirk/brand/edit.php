
<li class="active">品牌管理</li>
<li class="active">编辑品牌</li>
</ol>                
<hr class="darken"> 

<?php

use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>

<link type="text/css" href="<?php echo $themeUrl; ?>/libs/artdialog/skins/aero.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/artDialog.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate-plugin.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/autovalidate/validate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/form.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $themeUrl; ?>/libs/autovalidate/style.css" />

<div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading nopaddingbottom">
                <h4 class="panel-title">品牌信息</h4>
            </div>
            <div class="panel-body">
                <hr>
                <?php $form = ActiveForm::begin(['id' => 'basicForm', 'options' => ['name' => 'brandForm', 'class' => 'form-horizontal'], 'action' => Url::to(['/brand/save'])]); ?>
                <input type="hidden" name="id" value="<?php
                if (isset($brandInfo)) {
                    echo $brandInfo['id'];
                }
                ?>" />
                <input type='hidden' name="callback" value="" />
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger">*</span> 品牌名称：</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" pattern="required" alt="品牌名称不能为空" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 排序：</label>
                    <div class="col-sm-9">
                        <input type="text" name="sort" class="form-control" alt="排序必须是一个数字" empty="" pattern="int" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 网址：</label>
                    <div class="col-sm-9">
                        <input type="text" name="url" class="form-control" alt="网址格式不正确" empty="" pattern="url" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 所属分类：</label>
                    <div class="col-sm-9">
                        <?php $brandCategoryData = common\models\BrandCategory::getAllList(); ?>
                        <?php if ($brandCategoryData): ?>
                            <table class="table table-hover nomargin">
                                <?php foreach ($brandCategoryData as $item): ?>
                                    <tr>
                                        <td class="text-center" style="width: 50px;">
                                            <label class="ckbox ckbox-primary">
                                                <input type="checkbox" value="<?= $item['id'] ?>" name="category[]" <?php if(isset($brandInfo) && stripos(','.$brandInfo['category_ids'].',',','.$item['id'].',') !== false){?>checked="checked"<?php }?>/><span></span>
                                            </label>
                                        </td>
                                        <td><?= $item['name'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php else: ?>
                            系统暂无品牌分类，<a href='<?php echo Url::to(['/brand/create-category']);?>' class='text-danger'>请点击添加</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 描述：</label>
                    <div class="col-sm-9">
                        <?php

                        use kucha\ueditor\UEditor;

echo UEditor::widget([
                            'name' => 'description',
                            'clientOptions' => [
                                //编辑区域大小
                                'initialFrameHeight' => '200',
                                //设置语言
                                'lang' => 'zh-cn', //中文为 zh-cn
                                //定制菜单
                                'toolbars' => [
                                    [
                                        'fullscreen', 'source', '|', 'undo', 'redo', '|',
                                        'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                                        'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                                        'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                                        'directionalityltr', 'directionalityrtl', 'indent', '|',
                                        'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                                        'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                                        'simpleupload', 'insertimage', 'pagebreak', '|',
                                        'horizontal', 'date', 'time', 'spechars', '|',
                                        'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts',
                                    ],
                                ]
                            ]
                        ]);
                        ?>
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
<?php if (isset($brandInfo)): ?>
    <script type="text/javascript">
        $(function ()
        {
            var formObj = new Form('brandForm');
            var data = <?php echo Json::encode(ArrayHelper::toArray($brandInfo)); ?>;
            formObj.init(data);
        });
    </script>
<?php endif; ?>    