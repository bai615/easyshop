<?php
use yii\helpers\Url;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="UTF-8">
        <link type="text/css" href="<?php echo $themeUrl; ?>/css/admin.css" rel="stylesheet"/>
        <link type="text/css" href="<?php echo $themeUrl; ?>/libs/artdialog/skins/aero.css" rel="stylesheet"/>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/artDialog.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/plugins/iframeTools.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate-plugin.js"></script>
        <script type='text/javascript' src="<?php echo $themeUrl; ?>/js/admin.js"></script>
</head>
<body style="width:700px;min-height:400px;">
<div class="pop_win">
	<table class="spec" width="95%" cellspacing="0" cellpadding="0" border="0">
		<col width="40%" />
		<col width="65%" />
		<tr>
			<td>
				<h3>请选择规格</h3>
				<div class="cont" style='height:285px;'>
					<ul id="specs"></ul>

					<script type='text/html' id='specList'>
						<%for(var item in templateData){%>
							<%include('specLi',{'item':templateData[item]})%>
						<%}%>
					</script>

					<script type='text/html' id='specLi'>
					<li>
						<label><input type="radio" autocomplete="off" name="spec" onclick="selSpect(this,<%=item['id']%>)" value='{"id":"<%=item['id']%>","name":"<%=item['name']%>","type":"<%=item['type']%>","value":"<%=encodeJSON(item['value'])%>"}' /><%=item['name']%><%if(item['note']){%>[<%=item['note']%>]<%}%></label>
					</li>
					</script>
				</div>
				<p>没有找到需要的规格？</p>
				<p><button type="button" class="btn" onclick="addNewSpec()"><span class="add">添加新规格</span></button></p>
			</td>
			<td>
				<h4>规格预览区</h4>
				<div class="cont" style="width:360px;">
					<p>请在左侧列表选择规格！</p>
					<ul class="goods_spec_box"></ul>

					<!--商品规格列表-->
					<script type='text/html' id='showSpecTemplate'>
					<%var valueList = parseJSON(templateData['value']);%>
					<%for(var result in valueList){%>
					<li>
						<%if(templateData['type'] == 1){%>
						<span><%=valueList[result]%></span>
						<%}else{%>
						<span class="pic"><img src='<%=valueList[result]%>' width='30px' height='30px' /></span>
						<%}%>
					</li>
					<%}%>
					</script>

				</div>
			</td>
		</tr>
	</table>
</div>

<script type='text/javascript'>
$(function(){
	var specListHtml = template.render('specList',{'templateData':<?php echo $specInfo;?>});
	$('#specs').html(specListHtml);
});

//选择规格属性
function selSpect(_self,id)
{
	_self.focus();

	//设置当前选中规格的样式
	$('ul>li').removeClass('current');
	$(_self).parent().parent().addClass('current');

	//Ajax获取规格的详细信息
	$.getJSON("<?php echo Url::to(['/goods/spec-value-list']);?>",{'id':id,'random':Math.random()},function(data)
	{
		if(data)
		{
			var showSpecHtml = template.render('showSpecTemplate',{'templateData':data});
			$('.goods_spec_box').html(showSpecHtml);
		}
	});
}

//添加新规格
function addNewSpec()
{
	art.dialog.open('<?php echo Url::to(['/goods/spec-edit']);?>', {
		id:'addSpecWin',
	    title:'添加新规格',
	    okVal:'添加',
	    ok:function(iframeWin, topWin)
	    {
	    	var formObject = iframeWin.document.forms['specForm'];
	    	var paramArray = [];
	    	var elementsList = null;
	    	var valueName = ['name','type','note','value[]'];

	    	for(var index in valueName)
	    	{
	    		elementsList = iframeWin.document.getElementsByName(valueName[index]);
	    		if(elementsList.length > 0)
	    		{
		    		for(var j = 0;j < elementsList.length;j++)
		    		{
		    			if(valueName[index] == 'type' && elementsList[j].checked == false)
		    			{
		    				continue;
		    			}
		    			paramArray.push(valueName[index]+'='+elementsList[j].value);
		    		}
	    		}
	    	}

			$.getJSON(formObject.action,encodeURI(paramArray.join("&")),function(json){
				if(json.flag == 'success')
				{
		    		var specLiHtml = template.render('specLi',{'item':json.data});
		    		$('#specs').append(specLiHtml);

		    		//最后一项出发激活事件
		    		$('[name="spec"]:last').trigger('click');
		    		return true;
				}
				else
				{
					alert(json.message);
					return false;
				}
			});
	    }
	});
}
</script>
</body>
</html>