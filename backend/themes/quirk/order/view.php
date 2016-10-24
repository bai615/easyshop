<?php

use yii\helpers\Url;
use common\models\Order;
use common\models\Payment;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<li class="active">订单管理</li>
<li class="active">订单详情</li>
</ol>                
<hr class="darken"> 
<div class="col-md-12">
    <div class="panel">
        <div class="panel-heading nopaddingbottom">
            <h4 class="panel-title">订单信息</h4>
        </div>
        <div class="panel-body">
            <hr>
            <div class="panel">
                <div class="panel-heading">
                    基本信息
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <table class="table table-bordered table-primary nomargin">
                            <thead>
                                <tr>
                                    <th class="col-md-1 text-center">订单号</th>
                                    <td class="col-md-3"><?= $orderInfo['order_no'] ?></td>
                                    <th class="col-md-1 text-center">下单时间</th>
                                    <td class="col-md-3"><?php echo $orderInfo['create_time']; ?></td>
                                    <th class="col-md-1 text-center">订单状态</th>
                                    <td class="col-md-3"><?php echo Order::orderStatusText(Order::getOrderStatus($orderInfo)); ?></td>
                                </tr>
                                <tr>
                                    <th class="col-md-1 text-center">商品总金额</th>
                                    <td class="col-md-3">￥<?php echo sprintf('%.2f', $orderInfo['payable_amount']); ?></td>
                                    <th class="col-md-1 text-center">订单支付金额</th>
                                    <td class="col-md-3"><b style="color: #ba0505;font-size: 16px;">￥<?php echo sprintf('%.2f', $orderInfo['order_amount']); ?></b></td>
                                    <td class="col-md-1 text-center"></td>
                                    <td class="col-md-3"></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <button style="margin: 0 10px;" class="btn btn-success to_deliver" onclick="deliver(<?= $orderInfo['id'] ?>);">发货</button>
                                        <button style="margin: 0 10px;" class="btn btn-danger to_refundment" onclick="refundment(<?= $orderInfo['id'] ?>);">退款</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-6">
                        <table class="table table-bordered table-primary nomargin">
                            <thead>
                                <tr>
                                    <th class="" colspan="2">收货人信息</td>
                                </tr>
                                <tr>
                                    <th class="col-md-1 text-center">收货人姓名</th>
                                    <td class="col-md-3"><?php echo isset($orderInfo['accept_name']) ? $orderInfo['accept_name'] : ''; ?></td>
                                </tr>
                                <tr>
                                    <th class="col-md-1 text-center">收货地址</th>
                                    <td class="col-md-3">
                                        <?php echo isset($areaData[$orderInfo['province']]) ? $areaData[$orderInfo['province']] : ''; ?> 
                                        <?php echo isset($areaData[$orderInfo['city']]) ? $areaData[$orderInfo['city']] : ''; ?> 
                                        <?php echo isset($areaData[$orderInfo['area']]) ? $areaData[$orderInfo['area']] : ''; ?> 
                                        <?php echo isset($orderInfo['address']) ? $orderInfo['address'] : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="col-md-1 text-center">手机号码</th>
                                    <td class="col-md-3"><?php echo isset($orderInfo['mobile']) ? $orderInfo['mobile'] : ''; ?></td>
                                </tr>
                                <tr>
                                    <th class="col-md-1 text-center">备注</th>
                                    <td class="col-md-3"></td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered table-primary nomargin">
                            <thead>
                                <tr>
                                    <th class="" colspan="2">支付及配送方式</td>
                                </tr>
                                <tr>
                                    <th class="col-md-1 text-center">配送方式</th>
                                    <td class="col-md-3">快递</td>
                                </tr>
                                <tr>
                                    <th class="col-md-1 text-center">支付方式</th>
                                    <td class="col-md-3"><?php echo Payment::getPaymentById($orderInfo['pay_type'], 'name'); ?></td>
                                </tr>
                                <tr>
                                    <th class="col-md-1 text-center">物流公司</th>
                                    <td class="col-md-3">
                                        <?php echo isset($orderInfo['mobile']) ? $orderInfo['mobile'] : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="col-md-1 text-center">快递单号</th>
                                    <td class="col-md-3"><?php echo isset($orderInfo['mobile']) ? $orderInfo['mobile'] : ''; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-heading">
                    商品清单
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-primary table-striped nomargin">
                        <thead>
                            <tr>
                                <th style="width:70px;">商品图片</th>
                                <th>商品名称</th>
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
                                            <a title="<?php echo $goodsInfo['goods_name']; ?>" target="_blank" href="<?php echo Yii::$app->params['home_url'] . 'item_' . $goodsInfo['goods_id'] . '.html'; ?>">
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
                        <div class="col-xs-12 text-center">
                            <button style="margin: 0 10px;" class="btn btn-success to_deliver" onclick="deliver(<?= $orderInfo['id'] ?>);">发货</button>
                            <button style="margin: 0 10px;" class="btn btn-danger to_refundment" onclick="refundment(<?= $orderInfo['id'] ?>);">退款</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-1.11.3.min.js"></script>
    <?php
    $orderStatus = Order::getOrderStatus($orderInfo);
    pprint($orderStatus);
    ?>
<script type='text/javascript'>
//DOM加载完毕后运行
    $(function ()
    {
        initButton();
    });

    /**
     * 订单操作按钮初始化
     */
    function initButton()
    {
        //全部操作区域的按钮锁定
        $('button').attr('disabled','disabled');
        //发货
        <?php if(in_array($orderStatus, array(1, 4, 8, 9))):?>
        $('.to_deliver').removeAttr('disabled');
        <?php endif;?>

        //退款
        <?php if(in_array($orderStatus, array(4, 6, 9, 10))):?>
        $('.to_refundment').removeAttr('disabled');
        <?php endif;?>
    }
    
    //发货
function deliver(id)
{
	var tempUrl = '<?php echo Url::to(['/order/deliver','id'=>'idValue']);?>';
	tempUrl     = tempUrl.replace('idValue',id);

	var deliv = art.dialog.open(tempUrl,{
		id:'deliver',
	    title: '订单发货',
	    cancelVal:'关闭',
		okVal:'发货',
	    ok:function(iframeWin, topWin){
	    	var isResult = iframeWin.document.forms[0].onsubmit();
	    	if(isResult !== false)
	    	{
	    		iframeWin.document.forms[0].submit();
	    	}
	    	return false;
	    },
	    cancel:function (){
	    	return true;
		}
	});
}

//执行回调处理
function actionCallback(msg)
{
	msg ? alert(msg) : window.location.reload();
	window.setTimeout(function()
	{
		var list = art.dialog.list;
		for (var i in list)
		{
		    list[i].close();
		};
	},2500);
}
</script>