<?php

use yii\helpers\Url;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="container">
    <?php
    foreach (YII::$app->goods->getCategoryListTop() as $key => $firstCat) :
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $firstCat['name']; ?></h3>
                <ul>
                    <?php
                    foreach (YII::$app->goods->getCategoryByParentid($firstCat['id']) as $key => $secondCat) :
                        ?>
                        <li><?php echo $secondCat['name']; ?></li>
                        <?php
                    endforeach;
                    ?>
                </ul>
            </div>
            <div class="panel-body">
                <?php
                foreach (YII::$app->goods->getCategoryExtendList($firstCat['id'], 8) as $info) :
                    ?>
                    <div class="goods_block">
                        <p class="goods_img">
                            <a title="<?php echo $info['name']; ?>" target="_blank" href="<?php echo Url::to(['/site/products', 'id' => $info['id']]) ?>">
                                <img class="goods_img" src="<?php echo Yii::$app->params['upload_url'] . $info['img']; ?>" alt="<?php echo $info['name']; ?>" title="<?php echo $info['name']; ?>"/>
                            </a>
                        </p>
                        <p class="goods_title"><a title="<?php echo $info['name']; ?>" target="_blank" href="<?php echo Url::to(['/site/products', 'id' => $info['id']]) ?>"><?php echo $info['name']; ?></a></p>
                        <p class="goods_price">惊喜价：<b>￥<?php echo $info['sell_price']; ?></b></p>
                        <p class="goods_market_price">市场价：<s>￥<?php echo $info['market_price']; ?></s></p>
                        <p class="goods_operate">
                            <!--<a class="contrast" href="javascript:;"><i></i>对比</a>-->
                            <a class="buy" href="javascript:;" onclick="buy_now('<?php echo $info['id']; ?>');"><i>￥</i>购买</a>
                            <a class="focus" href="javascript:;" onclick="favorite_add(this, 'home', '<?php echo $info['id']; ?>');">
                                <i class="<?php if(in_array($info['id'], $favoriteArr)){echo 'focused';}?>"></i>收藏
                            </a>
                            <a class="addcart" href="javascript:;"><i></i>加入购物车</a>
                        </p>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
        </div>
        <?php
    endforeach;
    ?>
</div>
<script type="text/javascript">
    var favorite_url = "<?php echo Yii::$app->urlManager->createUrl('/site/favorite-add'); ?>";
</script>
<script src="<?php echo $themeUrl; ?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $themeUrl; ?>/js/goods_detail.js" type="text/javascript"></script>
