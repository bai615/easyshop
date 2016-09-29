
<li class="active">模型管理</li>
<li class="active">规格列表</li>
</ol>                
<hr class="darken"> 

<?php
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="panel">
    <div class="panel-heading">
        <a class="btn btn-primary" href="javascript:void(0);" onclick="addNewSpec();">添加规格</a>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered table-primary nomargin">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 45px;">
                            <label class="ckbox ckbox-primary">
                                <input type="checkbox" id="all_search"><span></span>
                            </label>
                        </th>
                        <th>规格名称</th>
                        <th>显示方式</th>
                        <th>规格数据</th>
                        <th class="text-center">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($model):
                        foreach ($model as $info):
                            ?>
                            <tr>
                                <td class="text-center">
                                    <label class="ckbox ckbox-primary">
                                        <input type="checkbox" name="ids" value="<?php echo $info['id']; ?>"><span></span>
                                    </label>
                                </td>
                                <td><?php echo $info['name']; ?></td>
                                <td>
                                    <?php
                                    if ($info['type'] == 1):
                                        echo '文字';
                                    else:
                                        echo '图片';
                                    endif;
                                    ?>
                                </td>
                                <td>
                                    <?php $_specValue = Json::decode($info['value']);?>
                                    <?php if($_specValue):?>
                                        <?php foreach($_specValue as $item):?>
                                            <?php if($info['type']==1):?>
                                                【<?=$item?>】
                                            <?php else:?>
                                                <img class="spec_photo" src="<?=$item?>" style="width:30px;height: 30px;" />
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </td>
                                <td class="text-center" style="width: 134px;">
                                    <a class="btn btn-success" href="javascript:addNewSpec(<?=$info['id'];?>);">编辑</a>
                                    <a class="btn btn-danger" href="javascript:void(0)" onclick="delModel({link: '<?php echo Url::to(['/goods/spec-del', 'id' => $info['id']]); ?>'})">删除</a>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div><!-- table-responsive -->
        <?=
        LinkPager::widget([
            'pagination' => $pages,
            'firstPageLabel' => "First",
            'prevPageLabel' => 'Prev',
            'nextPageLabel' => 'Next',
            'lastPageLabel' => 'Last',
            'options' => ['class' => 'pagination'],
        ]);
        ?>
    </div>

</div><!-- panel -->

<script type='text/javascript'>
//添加新规格
function addNewSpec(spec_id)
{
	var url = '<?php echo Url::to(['/goods/spec-edit','id'=>'spec_id_value']);?>';
	url = url.replace('spec_id_value',spec_id ? spec_id : 0);

	art.dialog.open(url,{
		id:'addSpecWin',
	    title:'规格设置',
	    okVal:'确定',
	    ok:function(iframeWin, topWin){
	    	var formObject = iframeWin.document.forms['specForm'];
			$.post(formObject.action,$(formObject).serialize(),function(json){
				if(json.flag == 'success')
				{
					window.location.reload();
					return true;
				}
				else
				{
					alert(json.message);
					return false;
				}
			},'json');
	    }
	});
}
</script>
