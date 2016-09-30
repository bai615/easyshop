
<li class="active">商品分类</li>
<li class="active">分类列表</li>
</ol>                
<hr class="darken"> 

<?php

use yii\helpers\Url;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="panel">
    <div class="panel-heading">
        <a class="btn btn-primary" href="<?php echo Url::to(['/category/create']); ?>">添加分类</a>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="list_table" class="table table-bordered table-primary nomargin">
                <thead>
                    <tr>
                        <th>排序</th>
                        <th>分类名称</th>
                        <th>首页显示</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($categoryInfo):
                        foreach ($categoryInfo as $info):
                            ?>
                            <tr id="<?= $info['id'] ?>" parent=<?= $info['parent_id'] ?>>
                                <td><input class="tiny" id="s<?= $info['id'] ?>" size="2" type="text" onblur="toSort(<?= $info['id'] ?>);" value="<?= $info['sort'] ?>" /></td>
                                <td>
                                    <img style='margin-left:<?= $info['floor'] * 20 ?>px' class="operator" src="<?=$themeUrl?>/images/close.gif" onclick="displayData(this);" alt="关闭" />
                                    <?= $info['name'] ?>
                                </td>
                                <td><?php if ($info['visibility'] == '1'): ?><span class="green">是</span><?php else: ?><span class="brown">否</span><?php endif; ?></td>
                                <td>
                                    <a class="btn btn-success" href="<?php echo Url::to(['/category/edit', 'id' => $info['id']]); ?>">编辑</a>
                                    <a class="btn btn-danger" href="javascript:void(0)" onclick="delModel({link: '<?php echo Url::to(['/category/remove', 'id' => $info['id']]); ?>'})">删除</a>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script language="javascript">
//折叠展示
function displayData(_self)
{
	if(_self.alt === "关闭")
	{
		jqshow($(_self).parent().parent().attr('id'), 'hide');
		$(_self).attr("src", "<?=$themeUrl?>/images/open.gif");
		_self.alt = '打开';
	}
	else
	{
		jqshow($(_self).parent().parent().attr('id'), 'show');
		$(_self).attr("src", "<?=$themeUrl?>/images/close.gif");
		_self.alt = '关闭';
	}
}

function jqshow(id,isshow) {
	var obj = $("#list_table tr[parent='"+id+"']");
	if (obj.length>0)
	{
		obj.each(function(i) {
			jqshow($(this).attr('id'), isshow);
		});
		if (isshow==='hide')
		{
			obj.hide();
		}
		else
		{
			obj.show();
		}
	}
}
//排序
function toSort(id)
{
	if(id!=='')
	{
		var va = $('#s'+id).val();
		var part = /^\d+$/i;
		if(va!=='' && va!==undefined && part.test(va))
		{
			$.get("{url:/goods/category_sort}",{'id':id,'sort':va}, function(data)
			{
				if(data==='1')
				{
					alert('修改商品分类排序成功!');
				}else
				{
					alert('修改商品分类排序错误!');
				}
			});
		}
	}
}
</script>