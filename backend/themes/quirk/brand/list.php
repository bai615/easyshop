
<li class="active">品牌管理</li>
<li class="active">品牌列表</li>
</ol>                
<hr class="darken"> 

<?php

use yii\widgets\LinkPager;
?>
<div class="panel">
    <div class="panel-heading">
        <a class="btn btn-primary" href="http://demo.blogtest.com/admin/article/create">新增</a>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered table-primary nomargin">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">排序</th>
                        <th>品牌名称</th>
                        <th class="text-left">网址</th>
                        <th class="text-center">品牌分类</th>
                        <th class="text-center">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($model):
                        foreach ($model as $info):
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $info['sort']; ?></td>
                                <td><?php echo $info['name']; ?></td>
                                <td class="text-left"><?php echo $info['url']; ?></td>
                                <td class="text-center"><?php echo YII::$app->goods->getBrandCategoryName($info['category_ids']); ?></td>
                                <td class="text-center" style="width: 134px;">
                                    <a class="btn btn-success" href="http://demo.blogtest.com/admin/article/create">编辑</a>
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
