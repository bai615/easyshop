
<li class="active">模型管理</li>
<li class="active">规格图库</li>
</ol>                
<hr class="darken"> 

<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="panel">
    <div class="panel-heading">
        <a class="btn btn-primary" href="javascript:void(0);" onclick="datadel();">批量删除</a>
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
                        <th>图片名称</th>
                        <th>图片</th>
                        <th>图片路径</th>
                        <th class="text-center">创建时间</th>
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
                                    <img class="spec_photo" style="width: 30px;height: 30px;" src="<?php echo Yii::$app->params['upload_url'] . $info['address']; ?>" />
                                </td>
                                <td>
                                    <?php echo $info['address']; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $info['create_time']; ?>
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

<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/layer/layer.js"></script> 
<script type="text/javascript">

    /*信息-批量删除*/
    function datadel() {
        var ids = [];
        $('input[name="ids"]:checked').each(function () {
            ids.push($(this).val());
        });
        if (ids.length === 0) {
            layer.msg('请选择图片信息!', {icon: 2, time: 1000});
        } else {
            layer.confirm('确定删除么？', function () {
                $.post("<?php echo Url::to(['/goods/spec-photo-del']); ?>", {ids: ids}, function (result) {
                    if(0 === result.errcode){
                        layer.msg(result.errmsg, {icon: 1, time: 1000});
                        setTimeout("location.replace(location.href)", 2000);
                    }else{
                        layer.msg(result.errmsg, {icon: 2, time: 1000});
                    }
                }, 'json');
            });
        }
    }
</script>
