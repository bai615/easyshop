<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use link\assets\AppAsset;

//use common\widgets\Alert;

$this->title = '跨境电商生态链网址大全';

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
<?php $this->head() ?>
    </head>
    <body>
<?php $this->beginBody() ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => Html::encode($this->title),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                ['label' => '首页', 'url' => ['/site/index']],
                ['label' => '添加分类', 'url' => ['/site/create-category']],
                ['label' => '添加链接', 'url' => ['/site/create-link']],
            ];
//            if (isset($this->context->data['shopUserInfo'])) {
//            } else {
            $menuItems[] = ['label' => '请登录', 'url' => ['/users/login']];
//            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
            ?>

            <div class="container">
                <p id="back-to-top"><a href="#top"><span></span>返回顶部</a></p>
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= $content ?>
                
            </div>
        </div>
        
        

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

                <p class="pull-right"><?= Yii::powered() ?>, Link template built for Bootstrap by @大漠胡杨.</p>
            </div>
        </footer>

<?php $this->endBody() ?>

    </body>
</html>
<?php $this->endPage() ?>
<script type="text/javascript">
            $(function () {
                //当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失 
                $(function () {
                    $(window).scroll(function () {
                        if ($(window).scrollTop() > 100) {
                            $("#back-to-top").fadeIn(1500);
                        } else
                        {
                            $("#back-to-top").fadeOut(1500);
                        }
                    });

                    //当点击跳转链接后，回到页面顶部位置 

                    $("#back-to-top").click(function () {
                        $('body,html').animate({scrollTop: 0}, 1000);
                        return false;
                    });
                });
            });
</script>
<script type="text/javascript" src="<?=$themeUrl?>/js/jquery.lazyload.min.js"></script>
<script type="text/javascript">
    $(function() {
          $("img").lazyload({ 
		  placeholder : "images/loading.gif",
                 effect: "fadeIn"
          });  
    });
</script>
