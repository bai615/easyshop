<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link type="text/css" href="<?php echo $themeUrl; ?>/css/admin.css" rel="stylesheet"/>
        <link type="text/css" href="<?php echo $themeUrl; ?>/libs/artdialog/skins/aero.css" rel="stylesheet"/>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/artDialog.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/plugins/iframeTools.js"></script>
    </head>
    <body>
        <ul class="tab" name="menu">
            <li class="selected"><a href='javascript:void(0)' hidefocus="true">本地上传</a></li>
            <li><a href='javascript:void(0)' hidefocus="true">使用图库</a></li>
        </ul>
        <div id='uploadMain' class="pop_win clearfix" style="width:550px;height:440px">
            <?php $form = ActiveForm::begin(['id' => 'pic-form', 'action' => Url::to(["/pic/upload-file", 'specIndex' => $specIndex]),'options'=>['enctype'=>'multipart/form-data']]); ?>
                <div id='uploadBox_0' class="uploadbox">
                    <input class="file" size="45" type="file" name="attach[]" />
                    <div class="tips">提示：选择的文件大小不超过3M，支持JPG、GIF和BMP格式。</div>
                </div>
                <div id='uploadBox_1' class="uploadbox" style="display:none">
                    从下面图库中选择：<input type="hidden" name="selectPhoto" />
                    <div class="list_photo">
                        <ul class="clearfix" name="menu">
                        </ul>
                    </div>
                    <div class="tips">提示：选择的文件大小不超过3M，支持JPG、GIF和BMP格式。</div>
                </div>
                <div id='uploadBox_2' class="uploadbox" style="display:none">
                    网络图片地址：<input type="text" class="normal" name="outerSrc" />
                    <div class="tips">提示：您只需要找到网络图片的网络地址，复制到下面的输入框<br />例如：http://www.example.com/images/pic.jpg</div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </body>
</html>
<script type='text/javascript'>
    jQuery(function () {
        $("[name='menu']>li").each(
                function (i) {
                    $(this).click(
                            function () {
                                $(this).siblings().removeClass("selected");
                                $(this).addClass("selected");
                                $(".uploadbox").hide();
                                $("#uploadBox_" + i).show();
                                if (i === 1)
                                {
                                    $.getJSON('{url:/pic/getPhotoList}', '',
                                            function (dataVal)
                                            {
                                                var dataStr = '';
                                                for (step = 0; step < dataVal.length; step++)
                                                {
                                                    dataStr += "<li><img onclick='appandVal(this);' width='50px' height='50px' src='{webroot:}" + dataVal[step]['address'] + "' title='" + dataVal[step]['address'] + "' /></li>";
                                                }
                                                $('.list_photo ul').html(dataStr);
                                            }
                                    );
                                }
                            }
                    );
                }
        );
    });

//动态增加图片的selected类
    function appandVal(obj)
    {
        //获取当前
        var imgSrc = $(obj).attr('title');

        //获取上一次的图片元素
        var inputSrc = $("input[name='selectPhoto']").val();

        if (imgSrc != inputSrc)
        {
            $('img[title="' + inputSrc + '"]').parent().removeClass('selected');
        }

        $('img[title="' + imgSrc + '"]').parent().addClass('selected');
        $("input[name='selectPhoto']").val(imgSrc);
    }
</script>