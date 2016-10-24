<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\models\FreightCompany;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="UTF-8">
        <link type="text/css" href="<?php echo $themeUrl; ?>/css/admin.css" rel="stylesheet"/>
        <link type="text/css" href="<?php echo $themeUrl; ?>/libs/autovalidate/style.css" rel="stylesheet"/>
        <link type="text/css" href="<?php echo $themeUrl; ?>/libs/artdialog/skins/aero.css" rel="stylesheet"/>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/artDialog.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/plugins/iframeTools.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate-plugin.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/autovalidate/validate.js"></script>
        <script type='text/javascript' src="<?php echo $themeUrl; ?>/js/admin.js"></script>
    </head>
    <body style="width:700px;min-height:200px;">
        <?php $form = ActiveForm::begin(['id' => 'basicForm', 'options' => ['name' => 'deliverForm', 'class' => 'form-horizontal'], 'action' => Url::to(['/order/deliver-save'])]); ?>
        <input type="hidden" name="id" value="<?= $orderInfo['id'] ?>" />
        <input type="hidden" name="order_no" value="<?= $orderInfo['order_no'] ?>" />
        <table width="95%" class="border_table" style="margin:10px auto">
            <colgroup>
                <col width="100px" />
                <col />
                <col width="100px" />
                <col />
            </colgroup>

            <tbody>
                <tr>
                    <th>订单号:</th><td align="left"><?= $orderInfo['order_no'] ?></td>
                    <th>下单时间:</th><td align="left"><?= $orderInfo['create_time'] ?></td>
                </tr>
                <tr>
                    <th>收货人姓名:</th><td align="left"><?php echo isset($orderInfo['accept_name']) ? $orderInfo['accept_name'] : ''; ?></td>
                    <th>手机号码:</th><td align="left"><?php echo isset($orderInfo['mobile']) ? $orderInfo['mobile'] : ''; ?></td>
                </tr>
                <tr>
                    <th>收货地址:</th>
                    <td align="left" colspan="3">
                        <?php echo isset($areaData[$orderInfo['province']]) ? $areaData[$orderInfo['province']] : ''; ?> 
                        <?php echo isset($areaData[$orderInfo['city']]) ? $areaData[$orderInfo['city']] : ''; ?> 
                        <?php echo isset($areaData[$orderInfo['area']]) ? $areaData[$orderInfo['area']] : ''; ?> 
                        <?php echo isset($orderInfo['address']) ? $orderInfo['address'] : ''; ?>
                    </td>
                </tr>
                <tr>
                    <th>物流公司:</th>
                    <td align="left" style="width:220px">
                        <select name="freight_id" pattern="required" alt="物流公司" style="display: inline-block;font-size: 12px;">
                            <option value="">请选择物流公司</option>
                            <?php foreach (FreightCompany::getAll() as $info): ?>
                                <option value="<?= $info['id'] ?>"><?= $info['freight_name'] ?></option>
                            <?php endforeach; ?>

                        </select>
                        <label>选择物流公司</label>
                    </td>
                    <th>配送单号:</th>
                    <td align="left"><input type="text" style="width: 200px;" class="normal" placeholder="请填写配送单号" name="delivery_code" pattern="required" /></td>
                </tr>
            </tbody>
        </table>
        <?php ActiveForm::end(); ?>
    </body>
</html>