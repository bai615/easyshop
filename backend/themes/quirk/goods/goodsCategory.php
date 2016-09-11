<?php
$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="UTF-8">
        <title></title>

        <link type="text/css" href="<?php echo $themeUrl; ?>/css/admin.css" rel="stylesheet"/>
        <link type="text/css" href="<?php echo $themeUrl; ?>/libs/artdialog/skins/aero.css" rel="stylesheet"/>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/artDialog.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/plugins/iframeTools.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate-plugin.js"></script>

    </head>
    <body style="width:650px;min-height:250px;">
        <div class="pop_win">

            <table class="form_table" style="margin-top: 0px;">
                <colgroup>
                    <col width="85px" />
                    <col />
                </colgroup>
                <th>所属分类：</th>
                <td id="categoryBox"></td>
            </table>

            <!--分类列表-->
            <script id="categoryListTemplate" type="text/html">
                <ul class="select">
                    <% for(var item in templateData) { %>
                        <% item = templateData[item] %>
                    <li onmouseover="showCategory(<%= item['id'] %>,<%= level %>);"><label><input name="categoryVal" type="checkbox" value="<%= item['id'] %>" onchange="selectCategory(this);" <% if (jQuery() . inArray(item['id'], checkedCategory) != -1) { %>checked="checked"<% } %> /><%= item['name'] %></label></li>
                    <% } %>
                </ul>
            </script>
        </div>
    </body>

    <script type="text/javascript">
        //分类层次数据结构
        categoryParentData = art.dialog.data('categoryParentData');

        //初始化被选中的分类ID
        checkedCategory = art.dialog.data('categoryValue') ? art.dialog.data('categoryValue') : [];

        $(function ()
        {
            //生成顶级分类信息
            var templateHtml = template.render('categoryListTemplate', {'templateData': categoryParentData[0], 'level': 0, 'checkedCategory': checkedCategory});
            $('#categoryBox').append(templateHtml);
        })

        //显示分类数据信息
        function showCategory(categoryId, level)
        {
            $('ul.select:gt(' + level + ')').remove();
            var childCategory = categoryParentData[categoryId];
            if (childCategory)
            {
                var templateHtml = template.render('categoryListTemplate', {'templateData': childCategory, 'level': level + 1, 'checkedCategory': checkedCategory});
                $('#categoryBox').append(templateHtml);
            }
        }

        //选择分类数据
        function selectCategory(_self)
        {
            var categoryId = $(_self).val();
            var valueIndex = jQuery.inArray(categoryId, checkedCategory);

            if ($(_self).attr('checked'))
            {
                (valueIndex == -1) ? checkedCategory.push(categoryId) : "";
            } else
            {
                (valueIndex == -1) ? "" : checkedCategory.splice(valueIndex, 1);
            }
            //更新分类数据值
            art.dialog.data('categoryValue', checkedCategory);
        }
    </script>
</html>