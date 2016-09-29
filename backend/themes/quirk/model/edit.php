
<li class="active">模型管理</li>
<li class="active">模型列表</li>
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
                <h4 class="panel-title">模型信息</h4>
            </div>
            <div class="panel-body">
                <hr>
                <?php $form = ActiveForm::begin(['id' => 'basicForm', 'options' => ['name' => 'modelForm', 'class' => 'form-horizontal'], 'action' => Url::to(['/model/update'])]); ?>
                <input type="hidden" name="id" value="" />
                <input type='hidden' name="callback" value="" />
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger">*</span> 模型名称：</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" pattern="required" alt="模型名称不能为空" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">添加扩展属性： <span class="text-danger"></span></label>
                    <div class="col-sm-9">
                        <button class="btn btn-success" type="button" onclick="addAttr()"><span class="add">添加扩展属性</span></button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span></label>
                    <div class="col-sm-9">
                        <table width="90%" class='table table-bordered table-primary table-striped nomargin'>
                            <thead>
                                <tr>
                                    <th>属性名</th>
                                    <th>操作样式</th>
                                    <th>选择项数据【每项数据之间用逗号','做分割】</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody id="attr_list"></tbody>

                            <!--属性列表-->
                            <script type='text/html' id='attrListTemplate'>
                                <%for(var item in templateData){%>
                                <%include('attrTrTemplate',{'item':templateData[item]})%>
                                <%}%>
                            </script>

                            <!--属性单行-->
                            <script type='text/html' id='attrTrTemplate'>
                                <tr class='td_c'>
                                    <td>
                                        <input name='attr[id][]' type='hidden' value="<%=item['id']%>" />
                                        <input name='attr[name][]' class='form-control' type='text' pattern='required' value="<%=item['name']%>" />
                                    </td>
                                    <td>
                                        <select class="form-control" name='attr[show_type][]'>
                                            <option value='1' <%if(item['type']=='1'){%>selected<%}%>>单选框</option>
                                            <option value='2' <%if(item['type']=='2'){%>selected<%}%>>复选框</option>
                                            <option value='3' <%if(item['type']=='3'){%>selected<%}%>>下拉框</option>
                                            <option value='4' <%if(item['type']=='4'){%>selected<%}%>>输入框</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class='form-control' name='attr[value][]' type='text' value="<%=item['value']%>" />
                                    </td>
                                    <td>
                                        <img class="operator" src="<?php echo $themeUrl; ?>/images/icon_del.gif" alt="删除" onclick="delAttr(this)" />
                                    </td>
                                </tr>
                                </script>
                            </table>
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
    <?php if (isset($modelInfo)): ?>
        <script type="text/javascript">
            $(function ()
            {
                var formObj = new Form('modelForm');
                var data = <?php echo Json::encode(ArrayHelper::toArray($modelInfo)); ?>;
                formObj.init(data);
            });
        </script>
    <?php endif; ?>    
    <script type='text/javascript'>
        $(function () {
            //初始化属性
            <?php if (isset($modelInfo) && ($modelInfo['model_attr'])): ?>
                var attrListHtml = template.render('attrListTemplate', {'templateData': <?php echo Json::encode(ArrayHelper::toArray($modelInfo['model_attr'])); ?>});
                $('#attr_list').html(attrListHtml);
            <?php endif; ?>
        });
        //添加一个模型属性
        function addAttr()
        {
            var attrTrHtml = template.render('attrTrTemplate', {'item': []});
            $('#attr_list').append(attrTrHtml);
        }

        //删除模型属性
        function delAttr(curr_attr)
        {
            $(curr_attr).parent().parent().remove();
        }
    </script>    