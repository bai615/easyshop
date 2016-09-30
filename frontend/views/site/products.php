<?php

use common\utils\CommonTools;
use yii\helpers\Url;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="container block_box">
    <div><span>您当前的位置：</span></div>

    <div class="goods_detail">
        <div id="preview" class="goods_photo">
            <div class="jqzoom" id="spec-n1">
                <img height="350" src="<?php echo Yii::$app->params['upload_url'] . $goodsInfo['img']; ?>" jqimg="<?php echo Yii::$app->params['upload_url'] . $goodsInfo['img']; ?>" width="350">
            </div>
            <div id="spec-n5">
                <div class="control" id="spec-left">
                    <img src="<?php echo $themeUrl; ?>/images/left.gif" />
                </div>
                <div id="spec-list">
                    <ul class="list-h">
                        <?php
                        if ($goodsInfo['photo']):
                            foreach ($goodsInfo['photo'] as $info):
                                ?>
                                <li>
                                    <img src="<?php echo Yii::$app->params['upload_url'] . $info['img']; ?>">
                                </li>
                                <?php
                            endforeach;
                            foreach ($goodsInfo['photo'] as $info):
                                ?>
                                <li>
                                    <img src="<?php echo Yii::$app->params['upload_url'] . $info['img']; ?>">
                                </li>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </ul>
                </div>
                <div class="control" id="spec-right">
                    <img src="<?php echo $themeUrl; ?>/images/right.gif" />
                </div>
            </div>
        </div>
        <div class="goods_info">
            <h2 class="goods_title"><?php echo isset($goodsInfo['name']) ? $goodsInfo['name'] : ""; ?></h2>
            <!--货品ID，当为商品时值为空-->
            <input type='hidden' id='product_id' alt='货品ID' value='' />
            <input type='hidden' id='goods_id' alt='商品ID' value='<?php echo $goodsInfo['id']; ?>' />
            <ul>
                <li class="goods_no">商品编号：<span id="goods_no"><?php echo $goodsInfo['goods_no'] ? $goodsInfo['goods_no'] : $goodsInfo['id']; ?></span></li>
                <?php if(($goodsInfo['brand_name'])):?>
                <li>品牌：<?php echo $goodsInfo['brand_name'];?></li>
                <?php endif;?>
                <li>销售价：
                    <b class="goods_price" id="real_price">
                        <?php
                        if ($goodsInfo['price_area']['minSellPrice'] != $goodsInfo['price_area']['maxSellPrice']):
                            ?>
                            ￥<?php echo $goodsInfo['price_area']['minSellPrice']; ?> - ￥<?php echo $goodsInfo['price_area']['maxSellPrice']; ?>
                            <?php
                        else:
                            ?>
                            ￥<?php echo $goodsInfo['sell_price']; ?>
                        <?php
                        endif;
                        ?>
                    </b>
                </li>
                <li>市场价：
                    <s id="market_price">
                        <?php
                        if ($goodsInfo['price_area']['minMarketPrice'] != $goodsInfo['price_area']['maxMarketPrice']):
                            ?>
                            ￥<?php echo $goodsInfo['price_area']['minMarketPrice']; ?> - ￥<?php echo $goodsInfo['price_area']['maxMarketPrice']; ?>
                            <?php
                        else:
                            ?>
                            ￥<?php echo $goodsInfo['market_price']; ?>
                        <?php
                        endif;
                        ?>
                    </s>
                </li>
                <li>
                    库存：现货<span>(<label id="store_nums"><?php echo $goodsInfo['store_nums']; ?></label>)</span>
                    <a class="favorite" onclick="favorite_add(this, 'item', '<?php echo $goodsInfo['id']; ?>');" href="javascript:void(0)">
                        <?php 
                        if(in_array($goodsInfo['id'], $favoriteArr)):
                            ?>
                            <i class="glyphicon glyphicon-star"></i><span>已收藏</span>
                            <?php
                        else:
                            ?>
                            <i class="glyphicon glyphicon-star-empty"></i><span>收藏此商品</span>
                            <?php
                        endif;
                        ?>
                    </a>
                </li>
                <li>顾客评分：<span class="goods_grade"><i style="width:<?php echo CommonTools::gradeWidth($goodsInfo['grade'], $goodsInfo['comments']); ?>px;"></i></span> (已有<?php echo $goodsInfo['comments']; ?>人评价)</li>
                <!--
                <li>配送至：</li>
                -->
            </ul>
            <div class="goods_current">
                <?php
                if($goodsInfo['is_del'] !=0 ):
                ?>
                    该商品已下架，不能购买，您可以看看其它商品！
                    <?php
                elseif ($goodsInfo['store_nums'] <= 0):
                    ?>
                    该商品已售完，不能购买，您可以看看其它商品！
                    <?php
                else:
                    ?>
                    <?php
                    if ($goodsInfo['spec_array']):
                        $specArray = json_decode($goodsInfo['spec_array'], true);
                        foreach ($specArray as $key => $item):
                            ?>
                            <dl name="specCols">
                                <dt><?php echo isset($item['name']) ? $item['name'] : ""; ?>：</dt>
                                <dd>
                                    <?php
                                    $specVal = explode(',', trim($item['value'], ','));
                                    foreach ($specVal as $key => $specValue):
                                        ?>
                                        <div class="goods_spec_value">
                                            <a href="javascript:void(0);" value='{"id":"<?php echo isset($item['id']) ? $item['id'] : ""; ?>","type":"<?php echo isset($item['type']) ? $item['type'] : ""; ?>","value":"<?php echo isset($specValue) ? $specValue : ""; ?>","name":"<?php echo isset($item['name']) ? $item['name'] : ""; ?>"}' ><?php echo $specValue; ?></a>
                                        </div>
                                        <?php
                                    endforeach;
                                    ?>
                                </dd>
                            </dl>
                            <?php
                        endforeach;
                        ?>
                            <dl class="">
                                <dt>已选：</dt><dd><span class="orange bold" id="specSelected"></span>&nbsp;</dd>
                            </dl>
                            <?php
                        endif;
                        ?>
                    <dl class="buy_num">
                        <dt>购买数量：</dt>
                        <dd>
                            <input class="" type="text" id="buyNums" onblur="checkBuyNums();" value="1" maxlength="5" />
                            <div class="resize">
                                <a class="add" href="javascript:modified(1);"></a>
                                <a class="reduce" href="javascript:modified(-1);"></a>
                            </div>
                        </dd>
                    </dl>
                    <button type="button" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-shopping-cart"></i> 加入购物车</button>
                    <button type="button" class="btn btn-lg btn-danger" onclick="buy_now('<?php echo $goodsInfo['id']; ?>');"><i>￥</i>立即购买</button>
                <?php
                endif;
                ?>
            </div>

        </div>
    </div>
</div>
<!-- -->
<div class="container block_box">

    <div class="goods_hots_box">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" title="热卖商品">热卖商品</h3>
            </div>
            <div class="panel-body">
                <ul>
                    <?php
                    foreach (Yii::$app->goods->getCommendHot() as $key => $hotInfo) :
                        ?>
                        <li>
                            <div class="goods_img">
                                <a href="<?php echo Url::to('home/products', array('id' => $hotInfo['goods_id'])); ?>" title="<?php echo $hotInfo['name']; ?>">
                                    <img src="<?php echo Yii::$app->params['upload_url'] . $hotInfo['img']; ?>" alt="<?php echo $hotInfo['name']; ?>"/>
                                </a>
                            </div>
                            <div class="goods_info">
                                <a class="goods_name" href="<?php echo Url::to('home/products', array('id' => $hotInfo['goods_id'])); ?>" title="<?php echo $hotInfo['name']; ?>"><?php echo $hotInfo['name']; ?></a>
                                <div class="goods_price">
                                    <span><s>￥<?php echo $goodsInfo['market_price']; ?></s></span>
                                    <span><b>￥<?php echo $goodsInfo['sell_price']; ?></b></span>
                                </div>
                            </div>
                        </li>
                        <?php
                    endforeach;
                    ?>

                </ul>
            </div>
        </div>
    </div>
    <div class="goods_desc_box">
        <ul id="myTab" class="nav nav-tabs">
            <li class="active">
                <a href="#goods_desc" data-toggle="tab">
                    商品介绍
                </a>
            </li>
            <li><a href="#ios" data-toggle="tab">商品评价</a></li>
            <li><a href="#jmeter" data-toggle="tab">购买记录</a></li>
            <li><a href="#ejb" data-toggle="tab">售前咨询</a></li>
            <li><a href="#ios" data-toggle="tab">商品评价</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active" id="goods_desc">
                <ul class="sale_infos">
                    <li title="<?php echo isset($goodsInfo['name']) ? $goodsInfo['name'] : ""; ?>">商品名称：<?php echo isset($goodsInfo['name']) ? $goodsInfo['name'] : ""; ?></li>

                    <?php if (isset($goodsInfo['brand']) && $goodsInfo['brand']): ?>
                        <li title="<?php echo isset($goodsInfo['brand']) ? $goodsInfo['brand'] : ""; ?>">品牌：<?php echo isset($goodsInfo['brand']) ? $goodsInfo['brand'] : ""; ?></li>
                    <?php endif; ?>

                    <?php if (isset($goodsInfo['weight']) && $goodsInfo['weight']) : ?>
                        <li title="<?php echo isset($goodsInfo['weight']) ? $goodsInfo['weight'] : ""; ?>g">商品毛重：<label id="weight"><?php echo isset($goodsInfo['weight']) ? $goodsInfo['weight'] : ""; ?></label></li>
                    <?php endif; ?>

                    <?php if (isset($goodsInfo['unit']) && $goodsInfo['unit']) : ?>
                        <li title="<?php echo isset($goodsInfo['unit']) ? $goodsInfo['unit'] : ""; ?>">单位：<?php echo isset($goodsInfo['unit']) ? $goodsInfo['unit'] : ""; ?></li>
                    <?php endif; ?>

                    <?php if (isset($goodsInfo['up_time']) && $goodsInfo['up_time']) : ?>
                        <li title="<?php echo isset($goodsInfo['up_time']) ? $goodsInfo['up_time'] : ""; ?>">上架时间：<?php echo isset($goodsInfo['up_time']) ? $goodsInfo['up_time'] : ""; ?></li>
                    <?php endif; ?>

                    <?php /** if (($attribute)) { ?>
                      <?php foreach ($attribute as $key => $item) { ?>
                      <li><?php echo isset($item['name']) ? $item['name'] : ""; ?>：<?php echo isset($item['attribute_value']) ? $item['attribute_value'] : ""; ?></li>
                      <?php } ?>
                      <?php } */ ?>
                </ul>
                <?php if (isset($goodsInfo['content']) && $goodsInfo['content']): ?>
                    <div class="salebox">
                        <p class="saledesc"><?php echo isset($goodsInfo['content']) ? $goodsInfo['content'] : ""; ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="tab-pane fade" id="ios">
                <p>iOS 是一个由苹果公司开发和发布的手机操作系统。最初是于 2007 年首次发布 iPhone、iPod Touch 和 Apple 
                    TV。iOS 派生自 OS X，它们共享 Darwin 基础。OS X 操作系统是用在苹果电脑上，iOS 是苹果的移动版本。</p>
            </div>
            <div class="tab-pane fade" id="jmeter">
                <p>jMeter 是一款开源的测试软件。它是 100% 纯 Java 应用程序，用于负载和性能测试。</p>
            </div>
            <div class="tab-pane fade" id="ejb">
                <p>Enterprise Java Beans（EJB）是一个创建高度可扩展性和强大企业级应用程序的开发架构，部署在兼容应用程序服务器（比如 JBOSS、Web Logic 等）的 J2EE 上。
                </p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var goods_id = '<?php echo $goodsInfo['id']; ?>';
    var get_product_url = "<?php echo Yii::$app->urlManager->createUrl('/site/get-product'); ?>";
    var favorite_url = "<?php echo Yii::$app->urlManager->createUrl('/site/favorite-add'); ?>";
</script>
<script src="<?php echo $themeUrl; ?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $themeUrl; ?>/js/jqueryzoom.js" type="text/javascript"></script>
<script src="<?php echo $themeUrl; ?>/js/goods_detail.js" type="text/javascript"></script>
