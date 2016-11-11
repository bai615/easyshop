<?php
use yii\helpers\Url;
$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="container block_box">
    <div class="message_box warning">
        <p>
            <i class="glyphicon glyphicon-warning-sign"></i><span class="message_text"><?php echo isset($message) ? $message : '操作错误'; ?></span>
        </p>
        <p>
            您现在可以去：
            
            <a class="blue" href="/">网站首页</a> | 
            <a class="blue" href="<?php echo Url::to(['/ucenter/order']); ?>">我的订单</a> | 
            <a class="blue" href="<?php echo Url::to(['/shopping/cart']); ?>">我的购物车</a>
        </p>
    </div>
</div>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/jquery.min.js"></script>
