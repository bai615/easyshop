<?php
$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/artDialog.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/plugins/iframeTools.js"></script>
<script type="text/javascript">
    art.dialog.tips("<?=$message?>");
    location.href = '<?=$url?>';
</script>