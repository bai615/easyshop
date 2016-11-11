<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<link href="<?php echo $themeUrl; ?>/libs/autovalidate/style.css" rel="stylesheet">
<div class="container block_box">
    <div class="breadcrumb"><span>您当前的位置：</span> <a href="/">首页</a> 》账户余额</div>
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
            </div>
        </div>
        <div class="col-md-10 ucenter_main">
            <h4>在线充值</h4>
            <?php $form = ActiveForm::begin(['id' => 'online-form', 'options' => ['novalidate' => 'true', 'name' => 'form'], 'action' => Url::to(['/common/do-pay'])]); ?>
            <table class="table member_info">
                <tbody>
                    <tr>
                        <th class="text-right" style="width:100px;">充值金额：</th>
                        <td>
                            <input class="form-control" alt="请输入充值的金额" pattern="float" name="recharge" type="text" style="display: inline-block;width: 300px;"/>
                            <label> 请输入充值的金额</label>
                        </td>
                        <td class="">&nbsp;</td>
                    </tr>
                    <tr>
                        <th class="text-right" style="width:100px;">充值方式：</th>
                        <td class="">
                            <ul class="list-group">
                                <?php
                                if ($paymentList):
                                    foreach ($paymentList as $value):
                                        ?>
                                        <li class="list-group-item" id="addressItem<?php echo $value['id']; ?>">
                                            <input type="radio" name="payment_id" type="radio" value="<?php echo $value['id']; ?>"/> <?php echo $value['name']; ?>
                                        </li>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </td>
                        <td class="">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="">&nbsp;</td>
                        <td class=""><button class="btn btn-lg btn-danger" id="embed-submit" type="submit">确定充值</button></td>
                        <td class="">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/autovalidate/validate.js"></script>
<script type="text/javascript">
    $("#embed-submit").click(function () {
        var paymentObj = $("input[type=radio][name=payment_id]:checked");
        if (0 === paymentObj.length) {
            alert("请选择支付方式");
            return false;
        }
        console.log(addressObj.length);
        console.log(paymentObj.length);
        return true;
    });
</script>