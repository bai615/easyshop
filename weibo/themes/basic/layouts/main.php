<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Url;
use yii\helpers\Html;
use weiboCommon\models\Userinfo;
use weibo\widget\NavWidget;
use weibo\widget\LeftWidget;
use weibo\widget\RightWidget;

$this->title = 'Weibo';
$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
$styleInfo = Userinfo::find()->select('style')->where('uid=1')->one();
$style = empty($styleInfo) ? 'default' : $styleInfo['style'];
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="stylesheet" href="<?php echo $themeUrl; ?>/skin/<?= $style ?>/css/nav.css" />
        <link rel="stylesheet" href="<?php echo $themeUrl; ?>/skin/<?= $style ?>/css/index.css" />
        <link rel="stylesheet" href="<?php echo $themeUrl; ?>/skin/<?= $style ?>/css/bottom.css" />
        <link rel="stylesheet" href="<?php echo $themeUrl; ?>/uploadify/uploadify.css"/>
        <script type="text/javascript" src='<?php echo $themeUrl; ?>/js/jquery-1.7.2.min.js'></script>
        <script type="text/javascript" src='<?php echo $themeUrl; ?>/js/nav.js'></script>
        <script type="text/javascript" src='<?php echo $themeUrl; ?>/uploadify/jquery.uploadify.min.js'></script>
        <script type="text/javascript" src='<?php echo $themeUrl; ?>/js/index.js'></script>
        <script type='text/javascript'>
            var PUBLIC = '<?php echo $themeUrl; ?>';
            var uploadUrl = '{:U("Common/uploadPic")}';
            var sid = '{:session_id()}';
            var ROOT = '__ROOT__';
            var commentUrl = "{:U('Index/comment')}";
            var getComment = '{:U("Index/getComment")}';
            var keepUrl = '{:U("Index/keep")}';
            var delWeibo = '{:U("Index/delWeibo")}';
        </script>


        <!-- =============================================================================== NAV ====================================================================== -->
        <?= NavWidget::widget();?>
        <!-- =============================================================================== NAV ====================================================================== -->

        <!--==========内容主体==========-->
        <div style='height:60px;opcity:10'></div>
        <div class="main">
            <!--=====左侧=====-->

            <!-- =============================================================================== LEFT ====================================================================== -->
            <?= LeftWidget::widget();?>
            <!-- =============================================================================== LEFT ====================================================================== -->
            <?= $content ?>

            <!--==========右侧==========-->
            <?= RightWidget::widget();?>
        </div>
            <!--==========内容主体结束==========-->

            <!--==========底部==========-->
            <div id="bottom">
                <div class='link'>
                    <dl>
                        <dt>后盾网论坛</dt>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                    </dl>
                    <dl>
                        <dt>后盾网论坛</dt>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                    </dl>
                    <dl>
                        <dt>后盾网论坛</dt>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                    </dl>
                    <dl>
                        <dt>后盾网论坛</dt>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                    </dl>
                    <dl>
                        <dt>后盾网论坛</dt>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                        <dd><a href="">后盾网免费视频教程</a></dd>
                    </dl>
                </div>
                <div id="copy">
                    <div>
                        <p>
                            版权所有：{$Think.config.COPY} 站长统计 All rights reserved, houdunwang.com services for Beijing 2008-2012 
                        </p>
                    </div>
                </div>
            </div>

            <!--==========转发输入框==========-->
            <div id='turn' class='hidden'>
                <div class="turn_head">
                    <span class='turn_text fleft'>转发微博</span>
                    <span class="close fright"></span>
                </div>
                <div class="turn_main">
                    <form action='{:U("Index/turn")}' method='post' name='turn'>
                        <p></p>
                        <div class='turn_prompt'>
                            你还可以输入<span id='turn_num'>140</span>个字</span>
                        </div>
                        <textarea name='content' sign='turn'></textarea>
                        <ul>
                            <li class='phiz fleft' sign='turn'></li>
                            <li class='turn_comment fleft'>
                                <label>
                                    <input type="checkbox" name='becomment'/>同时评论给<span class='turn-cname'></span>
                                </label>
                            </li>
                            <li class='turn_btn fright'>
                                <input type="hidden" name='id' value=''/>
                                <input type="hidden" name='tid' value=''/>
                                <input type="submit" value='转发' class='turn_btn'/>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            <!--==========转发输入框==========-->

            <!--==========表情选择框==========-->
            <div id="phiz" class='hidden'>
                <div>
                    <p>常用表情</p>
                    <span class='close fright'></span>
                </div>
                <ul>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/hehe.gif" alt="呵呵" title="呵呵" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/xixi.gif" alt="嘻嘻" title="嘻嘻" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/haha.gif" alt="哈哈" title="哈哈" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/keai.gif" alt="可爱" title="可爱" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/kelian.gif" alt="可怜" title="可怜" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/wabisi.gif" alt="挖鼻屎" title="挖鼻屎" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/chijing.gif" alt="吃惊" title="吃惊" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/haixiu.gif" alt="害羞" title="害羞" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/jiyan.gif" alt="挤眼" title="挤眼" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/bizui.gif" alt="闭嘴" title="闭嘴" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/bishi.gif" alt="鄙视" title="鄙视" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/aini.gif" alt="爱你" title="爱你" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/lei.gif" alt="泪" title="泪" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/touxiao.gif" alt="偷笑" title="偷笑" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/qinqin.gif" alt="亲亲" title="亲亲" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/shengbin.gif" alt="生病" title="生病" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/taikaixin.gif" alt="太开心" title="太开心" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/ldln.gif" alt="懒得理你" title="懒得理你" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/youhenhen.gif" alt="右哼哼" title="右哼哼" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/zuohenhen.gif" alt="左哼哼" title="左哼哼" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/xiu.gif" alt="嘘" title="嘘" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/shuai.gif" alt="衰" title="衰" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/weiqu.gif" alt="委屈" title="委屈" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/tu.gif" alt="吐" title="吐" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/dahaqian.gif" alt="打哈欠" title="打哈欠" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/baobao.gif" alt="抱抱" title="抱抱" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/nu.gif" alt="怒" title="怒" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/yiwen.gif" alt="疑问" title="疑问" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/canzui.gif" alt="馋嘴" title="馋嘴" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/baibai.gif" alt="拜拜" title="拜拜" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/sikao.gif" alt="思考" title="思考" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/han.gif" alt="汗" title="汗" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/kun.gif" alt="困" title="困" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/shuijiao.gif" alt="睡觉" title="睡觉" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/qian.gif" alt="钱" title="钱" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/shiwang.gif" alt="失望" title="失望" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/ku.gif" alt="酷" title="酷" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/huaxin.gif" alt="花心" title="花心" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/heng.gif" alt="哼" title="哼" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/guzhang.gif" alt="鼓掌" title="鼓掌" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/yun.gif" alt="晕" title="晕" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/beishuang.gif" alt="悲伤" title="悲伤" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/zuakuang.gif" alt="抓狂" title="抓狂" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/heixian.gif" alt="黑线" title="黑线" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/yinxian.gif" alt="阴险" title="阴险" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/numa.gif" alt="怒骂" title="怒骂" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/xin.gif" alt="心" title="心" /></li>
                    <li><img src="<?php echo $themeUrl; ?>/images/phiz/shuangxin.gif" alt="伤心" title="伤心" /></li>
                </ul>
            </div>
            <!--==========表情==========-->

            <!--[if IE 6]>
                <script type="text/javascript" src="<?php echo $themeUrl; ?>/js/DD_belatedPNG_0.0.8a-min.js"></script>
                <script type="text/javascript">
                    DD_belatedPNG.fix('#top','background');
                    DD_belatedPNG.fix('.logo','background');
                    DD_belatedPNG.fix('#sech_text','background');
                    DD_belatedPNG.fix('#sech_sub','background');
                    DD_belatedPNG.fix('.send_title','background');
                    DD_belatedPNG.fix('.icon','background');
                    DD_belatedPNG.fix('.ta_right','background');
                </script>
            <![endif]-->
    </body>
</html>