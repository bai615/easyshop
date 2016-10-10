
<li class="active">订单管理</li>
<li class="active">订单列表</li>
</ol>                
<hr class="darken"> 

<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="panel">
    <div class="panel-heading">
        <a class="btn btn-primary" href="<?php echo Url::to(['/goods/create']); ?>">添加订单</a>
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
                        <th>订单号</th>
                        <th class="text-center">收货人</th>
                        <th class="text-center">支付状态</th>
                        <th class="text-center">发货状态</th>
                        <th class="text-center">支付方式</th>
                        <th class="text-center">用户名</th>
                        <th class="text-center">下单时间</th>
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
                                    <?php echo $info['order_no']; ?>
                                </td>
                                <td class="text-center">
                                   <?php echo $info['accept_name']; ?>
                                </td>
                                <td class="text-center"><?php echo \common\models\Order::getOrderPayStatusText($info); ?></td>
                                <td class="text-center"><?php echo \common\models\Order::getOrderDistributionStatusText($info); ?></td>
                                <td class="text-center"><?php echo \common\models\Payment::getPaymentById($info['pay_type'],'name'); ?></td>
                                <td class="text-center"><?php echo \common\models\User::getNameById($info['user_id']); ?></td>
                                <td class="text-center"><?php echo $info['create_time']; ?></td>
                                <td class="text-center" style="width: 190px;">
                                    <a class="btn btn-primary" href="<?php echo Url::to(['/goods/edit', 'id' => $info['id']]); ?>">查看</a>
                                    <a class="btn btn-success" href="<?php echo Url::to(['/goods/edit', 'id' => $info['id']]); ?>">编辑</a>
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
    //修改上下架
    function changeIsDel(gid, obj)
    {
        var selectedValue = $(obj).find('option:selected').val();
        $.getJSON("<?php echo Url::to(['/goods/goods-status']); ?>", {"id": gid, "type": selectedValue}, function (data) {
            if ('finish' === data.result) {
                location.replace(location.href);
            }
        }, 'json');
    }
    //上下架操作
    function goods_stats(type)
    {
        var ids = [];
        $('input[name="ids"]:checked').each(function () {
            ids.push($(this).val());
        });
        if (ids.length === 0) {
            layer.msg('请选择要操作的商品!', {icon: 2, time: 1000});
        } else {
            layer.confirm('确定将选中的商品进行操作吗？', function () {
                var urlVal = "<?php echo Url::to(['/goods/goods-status', 'type' => 'typeValue']); ?>";
                urlVal = urlVal.replace("typeValue", type);
                $.getJSON(urlVal, {id: ids}, function (data) {
                    if ('finish' === data.result) {
                        location.replace(location.href);
                    }
                }, 'json');
            });
        }
    }
    /*删除单条信息*/
    function delOneData(obj, id) {
        layer.confirm('确定要删除吗？', function (index) {
            $.post("<?php echo Url::to(['/goods/remove']); ?>", {ids: id}, function (result) {
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
            layer.msg('请选择要删除的商品!', {icon: 2, time: 1000});
        } else {
            layer.confirm('确定要删除选中的商品吗？', function () {
                var urlVal = "<?php echo Url::to(['/goods/remove']); ?>";
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
