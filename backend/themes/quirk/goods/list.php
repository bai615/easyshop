
<li class="active">商品管理</li>
<li class="active">商品列表</li>
</ol>                
<hr class="darken"> 

<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="panel">
    <div class="panel-heading">
        <a class="btn btn-primary" href="<?php echo Url::to(['/goods/create']); ?>">新增</a>
        <a class="btn btn-danger" href="javascript:delData();">删除</a>
        <a class="btn btn-success" href="javascript:goods_stats('up');">上架</a>
        <a class="btn btn-warning" href="javascript:goods_stats('down');">下架</a>
        <!--
        <a class="btn btn-info" href="">回收站</a>
        -->
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
                        <th>商品名称</th>
                        <th class="text-center" style="width:120px;">分类</th>
                        <th class="text-center">销售价格</th>
                        <th class="text-center">库存</th>
                        <th class="text-center">状态</th>
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
                                    <img src="<?php echo Yii::$app->params['upload_url'] . $info['img']; ?>" alt="<?php echo $info['name']; ?>" title="<?php echo $info['name']; ?>" style="width: 50px;height: 50px;float: left;margin-right: 10px;"/>
                                    <div><a href="<?php echo Yii::$app->params['home_url'] . 'item_' . $info['id'] . '.html'; ?>" target="_blank"><?php echo $info['name']; ?></a></div>
                                </td>
                                <td class="text-center"><?php echo YII::$app->goods->getCategoryName($info['id']); ?></td>
                                <td class="text-center"><?php echo $info['sell_price']; ?></td>
                                <td class="text-center"><?php echo $info['store_nums']; ?></td>
                                <td class="text-center">
                                    <select onchange="changeIsDel(<?php echo $info['id']; ?>, this)">
                                        <option value="up" <?php echo $info['is_del'] == 0 ? 'selected' : ''; ?>>上架</option>
                                        <option value="down" <?php echo $info['is_del'] == 2 ? 'selected' : ''; ?>>下架</option>
                                    </select>
                                </td>
                                <td class="text-center" style="width: 134px;">
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
