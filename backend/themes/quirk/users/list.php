
<li class="active">会员管理</li>
<li class="active">会员列表</li>
</ol>                
<hr class="darken"> 

<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="panel">
    <div class="panel-heading">
        <a class="btn btn-primary" href="<?php echo Url::to(['/users/create']); ?>">添加会员</a>
        <a class="btn btn-danger" href="javascript:delData();">批量删除</a>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered table-primary nomargin">
                <thead>
                    <tr>
                        <th class="text-center">
                            <label class="ckbox ckbox-primary">
                                <input type="checkbox" id="all_search"><span></span>
                            </label>
                        </th>
                        <th class="text-center">用户名</th>
                        <th class="text-center">真实姓名</th>
                        <th class="text-center">性别</th>
                        <th class="text-center">电子邮箱</th>
                        <th class="text-center">余额</th>
                        <th class="text-center">状态</th>
                        <th class="text-center">注册时间</th>
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
                                <td>
                                    <?php echo $info['username']; ?>
                                </td>
                                <td class="text-center">
                                   <?php echo $info->members['true_name']; ?>
                                </td>
                                <td class="text-center"><?php echo $info->members['sex']; ?></td>
                                <td class="text-center"><?php echo $info->members['email']; ?></td>
                                <td class="text-center"><?php echo $info->members['balance']; ?></td>
                                <td class="text-center"><?php echo \common\utils\CommonTools::userStatusText($info->members['status']); ?></td>
                                <td class="text-center"><?php echo $info['created_time']; ?></td>
                                <td class="text-center" style="width: 134px;">
                                    <a class="btn btn-success" href="<?php echo Url::to(['/users/edit', 'id' => $info['id']]); ?>">编辑</a>
                                    <a class="btn btn-danger" href="javascript:void(0)"  onclick="delOneData(this, '<?php echo $info['id']; ?>')">删除</a>
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
    /*删除单条信息*/
    function delOneData(obj, id) {
        layer.confirm('确定要删除吗？', function (index) {
            $.post("<?php echo Url::to(['/users/remove']); ?>", {ids: id}, function (result) {
                if (0 === result.errcode) {
                    layer.msg(result.errmsg, {icon: 1, time: 1000});
                    setTimeout("location.replace(location.href)", 2000);
                } else {
                    layer.msg(result.errmsg, {icon: 2, time: 1000});
                }
            }, 'json');
        });
    }
    /*批量删除信息*/
    function delData()
    {
        var ids = [];
        $('input[name="ids"]:checked').each(function () {
            ids.push($(this).val());
        });
        if (ids.length === 0) {
            layer.msg('请选择要删除的会员!', {icon: 2, time: 1000});
        } else {
            layer.confirm('确定要删除选中的会员吗？', function () {
                var urlVal = "<?php echo Url::to(['/users/remove']); ?>";
                $.post(urlVal, {ids: ids}, function (result) {
                    if (0 === result.errcode) {
                        layer.msg(result.errmsg, {icon: 1, time: 1000});
                        setTimeout("location.replace(location.href)", 2000);
                    } else {
                        layer.msg(result.errmsg, {icon: 2, time: 1000});
                    }
                }, 'json');
            });
        }
    }
</script>
