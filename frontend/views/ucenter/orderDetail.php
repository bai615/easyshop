<?php
use yii\helpers\Url;
use common\models\Order;
use common\models\Payment;
$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="container block_box">
    <div class="breadcrumb"><span>您当前的位置：</span> <a href="/">首页</a> 》我的订单</div>
    <div class="ucenter_box">
        <div class="col-md-2 ucenter_menu">
            <div class="list-group">
                <?php
                if ($this->context->menuData):
                    foreach ($this->context->menuData as $key => $info):
                        ?>
                        <a href="<?php echo Url::to([$info['url']]); ?>" class="list-group-item <?php if ($key == $this->context->currentMenu) {
                    echo 'active';
                } ?>"><?php echo $info['name']; ?></a>
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
            <h4>订单详情</h4>
            <table class="table">
                <tbody>
                    <tr>
                        <td>订单号：<?php echo $orderInfo['order_no']; ?></td>
                        <td>下单时间：<?php echo $orderInfo['create_time']; ?></td>
                        <td>状态：<?php echo Order::orderStatusText(Order::getOrderStatus($orderInfo)); ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="panel panel-default accept_info">
                <div class="panel-heading">
                    <h3 class="panel-title">收货人信息</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>收货人：</th>
                                <td><?php echo isset($orderInfo['accept_name']) ? $orderInfo['accept_name'] : ''; ?></td>
                            </tr>
                            <tr>
                                <th>收货地址：</th>
                                <td>
                                    <?php echo isset($areaData[$orderInfo['province']]) ? $areaData[$orderInfo['province']] : ''; ?> 
                                    <?php echo isset($areaData[$orderInfo['city']]) ? $areaData[$orderInfo['city']] : ''; ?> 
                                    <?php echo isset($areaData[$orderInfo['area']]) ? $areaData[$orderInfo['area']] : ''; ?> 
                                    <?php echo isset($orderInfo['address']) ? $orderInfo['address'] : ''; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>手机号码：</th>
                                <td><?php echo isset($orderInfo['mobile']) ? $orderInfo['mobile'] : ''; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default pay_info">
                <div class="panel-heading">
                    <h3 class="panel-title">支付及配送方式</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>配送方式：</th>
                                <td>快递</td>
                            </tr>
                            <tr>
                                <th>支付方式：</th>
                                <td>
                                    <?php echo Payment::getPaymentById($orderInfo['pay_type'], 'name'); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>物流公司：</th>
                                <td><?php echo isset($orderInfo['mobile']) ? $orderInfo['mobile'] : ''; ?></td>
                            </tr>
                            <tr>
                                <th>快递单号：</th>
                                <td><?php echo isset($orderInfo['mobile']) ? $orderInfo['mobile'] : ''; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default goods_detail_info">
                <div class="panel-heading">
                    <h3 class="panel-title">商品清单</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="goods_img">商品图片</th>
                                <th class="goods_name">商品名称</th>
                                <th>商品价格</th>
                                <th>商品数量</th>
                                <th>小计</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($orderInfo->orderGoods):
                                foreach ($orderInfo->orderGoods as $goodsInfo):
                                    ?>
                                    <tr>
                                        <td><img class="goods_img" src="<?php echo Yii::$app->params['upload_url'] . $goodsInfo['img']; ?>" width="66px" height="66px" alt="<?php echo $goodsInfo['goods_name']; ?>" title="<?php echo $goodsInfo['goods_name']; ?>" /></td>
                                        <td class="goods_title">
                                            <a title="<?php echo $goodsInfo['goods_name']; ?>" target="_bank" href="<?php echo Url::to(['site/products', 'id' => $goodsInfo['goods_id']]); ?>">
                                                <?php echo isset($goodsInfo['goods_name']) ? $goodsInfo['goods_name'] : ''; ?>
                                            </a>
                                        </td>
                                        <td>￥<?php echo isset($goodsInfo['real_price']) ? $goodsInfo['real_price'] : ''; ?></td>
                                        <td><?php echo isset($goodsInfo['goods_nums']) ? $goodsInfo['goods_nums'] : ''; ?></td>
                                        <td>￥<?php echo sprintf('%.2f', $goodsInfo['real_price'] * $goodsInfo['goods_nums']); ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                    <div class="" style="overflow: hidden; ">
                        <div class="col-xs-12 text-right">商品总金额：￥<?php echo sprintf('%.2f', $orderInfo['payable_amount']); ?></div>
                        <div class="col-xs-12 text-right"> 订单支付金额： <b style="color: #ba0505;font-size: 30px;">￥<?php echo sprintf('%.2f', $orderInfo['order_amount']); ?></b> </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/jquery.min.js"></script>