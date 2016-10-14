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
                                <i class="<?php if (in_array($info['id'], $favoriteArr)) {
                echo 'focused';
            } ?>"></i>收藏
                            </a>
                            <a class="addcart" href="javascript:;" _id="<?=$info['id']?>" _src="<?php echo Yii::$app->params['upload_url'] . $info['img']; ?>"><i></i>加入购物车</a>
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
<script src="<?php echo $themeUrl; ?>/js/jquery.fly.min.js" type="text/javascript"></script>
<script>
    $('.addcart').on('click', addCart);
    function addCart(event) {
        var img_src = $(this).attr('_src');
        var offset = $('#myCart').offset(), flyer = $('<img width="61" height="61" class="u-flyer" src="' + img_src + '"/>');
        var btn_pos_top = $(".addcart").offset().top - 50;
        var car_pos_top = $(window).height() - 900;
        flyer.fly({
            start: {
                left: event.pageX,
                top: btn_pos_top
            },
            end: {
                left: offset.left + 10,
                top: car_pos_top,
                width: 0,
                height: 0
            },
            onEnd: function () {
                //$(".addcar").unbind('click');
                this.destory();
            }
        });
        var goods_id = $(this).attr('_id');
        $.post('<?php echo Url::to(["/shopping/join-cart"]); ?>', {"goods_id": goods_id, "type": 'goods', "goods_num": 1, "random": Math.random}, function (content) {
            if (content.errcode === 0)
            {
                //获取购物车信息
                $.getJSON('<?php echo Url::to(["/shopping/show-cart"]); ?>', {"random": Math.random}, function (json)
                {
                    $('[name="mycart_count"]').text(json.count);
                    $('[name="mycart_sum"]').text(json.sum);

                    //更新顶部购物车下拉框信息
                    var cartTemplate = template.render('cartTemplete', {'goodsData': json.data, 'goodsCount': json.count, 'goodsSum': json.sum});
                    $('#w2').html(cartTemplate);
                });
            } else
            {
                alert(content.errmsg);
            }
        }, 'json');
    }
</script> 