<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="container block_box">
    <div class="breadcrumb"><span>您当前的位置：</span> <a href="/">首页</a> 》我的收藏</div>
    <div class="ucenter_box">
        <div class="col-md-2 ucenter_menu">
            <div class="list-group">
                <?php
                if ($this->context->menuData):
                    foreach ($this->context->menuData as $key => $info):
                        ?>
                        <a href="<?php echo Url::to([$info['url']]); ?>" class="list-group-item <?php
                        if ($key == $this->context->currentMenu) {
                            echo 'active';
                        }
                        ?>"><?php echo $info['name']; ?></a>
                           <?php
                       endforeach;
                   endif;
                   ?>
                <!--
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="divider"></a>
                <a href="#" class="list-group-item">Link</a>
                -->
            </div>
        </div>
        <div class="col-md-10 ucenter_main">
            <h4>我的收藏</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="width:350px;">商品名称</th>
                        <th style="width:160px;">收藏时间</th>
                        <th>商品价格</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($favoriteList):
                        foreach ($favoriteList as $favoriteInfo):
                            ?>
                            <tr>
                                <td>
                                    <a title="<?php echo $favoriteInfo->goods['name']; ?>" target="_bank" href="<?php echo Url::to(['site/products', 'id' => $favoriteInfo->goods['id']]); ?>">
                                        <img class="goods_img" src="<?php echo Yii::$app->params['upload_url'] . $favoriteInfo->goods['img']; ?>" width="66px" height="66px" alt="<?php echo $favoriteInfo->goods['name']; ?>" title="<?php echo $favoriteInfo->goods['name']; ?>" />
                                    </a>
                                </td>
                                <td>
                                    <a title="<?php echo $favoriteInfo->goods['name']; ?>" target="_bank" href="<?php echo Url::to(['site/products', 'id' => $favoriteInfo->goods['id']]); ?>">
                                        <?php echo $favoriteInfo->goods['name']; ?>
                                    </a>
                                </td>
                                <td>
                                    <?php echo $favoriteInfo['time']; ?>
                                </td>
                                <td>
                                    <?php echo $favoriteInfo->goods['sell_price']; ?>
                                </td>
                                <td>
                                    <p><a href="">加入购物车</a></p>
                                    <p><a href="">取消收藏</a></p>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
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

    </div>
</div>