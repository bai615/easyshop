
<li class="active">商品管理</li>
<li class="active">商品列表</li>
</ol>                
<hr class="darken"> 

<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="panel">
    <div class="panel-heading">
        <a class="btn btn-primary" href="http://demo.blogtest.com/admin/article/create">新增</a>
        <a class="btn btn-danger" href="http://demo.blogtest.com/admin/article/create">删除</a>
        <a class="btn btn-success" href="http://demo.blogtest.com/admin/article/create">上架</a>
        <a class="btn btn-warning" href="http://demo.blogtest.com/admin/article/create">下架</a>
        <!--
        <a class="btn btn-info" href="http://demo.blogtest.com/admin/article/create">回收站</a>
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
                                    <div><?php echo $info['name']; ?></div>
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
                                    <a class="btn btn-danger" href="javascript:void(0)">删除</a>
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
