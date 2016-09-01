<?php

use yii\bootstrap\ActiveForm;
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
            <input type='hidden' name='<?php echo $key; ?>' value='<?php echo $item; ?>' />
            <?php
        endforeach;
        ?>
<?php ActiveForm::end(); ?>
    </body>
    <script type='text/javascript'>
        window.document.forms[0].submit();
    </script>
</html>