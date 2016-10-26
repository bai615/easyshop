<?php

use yii\bootstrap\ActiveForm;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <p>please wait...</p>
        <?php $form = ActiveForm::begin(['id' => 'pay-form', 'method' => $method, 'action' => $submitUrl]); ?>
        <?php
        foreach ($sendData as $key => $item) :
            ?>
            <input type='hidden' name='<?php echo $key; ?>' value="<?php echo $item; ?>" />
            <?php
        endforeach;
        ?>
<?php ActiveForm::end(); ?>
    </body>
    <script type="text/javascript" src="<?php echo $themeUrl; ?>/js/jquery.min.js"></script>
    <script type='text/javascript'>
        <?php if('BalancePay'!=$pay_type):?>
        $('input[name=_csrf]').remove();
        <?php endif;?>
        window.document.forms[0].submit();
    </script>
</html>