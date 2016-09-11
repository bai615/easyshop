<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use backend\models\Menu;

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
        <!--
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery.js"></script>
        <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/bootstrap/js/bootstrap.js"></script>
        -->
    </head>
    <body>
        <?php $this->beginBody() ?>
        <!--
        <?php
        NavBar::begin([
            'brandLabel' => 'My Company',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);

        NavBar::end();
        ?>
        
        -->
        <header>
            <div class="headerpanel">
                <div class="logopanel">
                    <h2><a href="#">管理系统</a></h2>
                </div><!-- logopanel -->

                <div class="headerbar">
                    <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>

                    <div class="searchpanel" style="display: none;">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                            </span>
                        </div><!-- input-group -->
                    </div>

                    <div class="header-right">
                        <ul class="headermenu">
                            <li>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-logged" data-toggle="dropdown">
                                        <img src="/themes/quirk/images/avatar/small.jpg" alt="头像">
                                        test                                <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="/admin/user/change-password"><i class="fa fa-cog"></i> 修改密码</a></li>
                                        <li><a href="<?php echo Url::to(['/site/logout']) ?>" data-method="post" ><i class="fa fa-sign-out"></i> 退出</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div><!-- header-right -->
                </div><!-- headerbar -->
            </div><!-- header-->
        </header>

        <section>

            <div class="leftpanel">
                <div class="leftpanelinner">

                    <!-- ################## LEFT PANEL PROFILE ################## -->

                    <div class="media leftpanel-profile">
                        <div class="media-left">
                            <a href="#">
                                <img src="/themes/quirk/images/avatar/small.jpg" alt="" class="media-object img-circle">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                test
                            </h4>
                            <span>管理员</span>
                        </div>
                    </div><!-- leftpanel-profile -->
                    <div class="tab-content">

                        <div class="tab-pane active" id="mainmenu">
                            <h5 class="sidebar-title">菜单</h5>
                            <!-- sidebar组件 -->
                            <ul class="nav nav-pills nav-stacked nav-quirk">
                                <?php
                                $menuList = Menu::getMenu();
                                if ($menuList):
                                    foreach ($menuList as $menuInfo):
                                        ?>
                                        <li class="<?php
                                        if ($this->context->layoutData['controller'] == $menuInfo['controller']) {
                                            echo 'nav-parent active';
                                        } else if (!empty($menuInfo['children'])) {
                                            echo 'nav-parent';
                                        }
                                        ?>"><a href="<?php
                                                if (empty($menuInfo['children'])) {
                                                    echo Url::to([$menuInfo['url']]);
                                                } else {
                                                    echo '#';
                                                }
                                                ?>"><i class="fa <?= $menuInfo['icon']; ?>"></i><span><?= $menuInfo['name']; ?></span></a>
                                            <?php
                                            if ($menuInfo['children']):
                                                ?>
                                                <ul class="children">
                                                    <?php
                                                    foreach ($menuInfo['children'] as $childInfo):
                                                        ?>
                                                        <li class="<?php
                                                        if ($this->context->layoutData['action'] == $childInfo['action']) {
                                                            echo 'active';
                                                        }
                                                        ?>"><a href="<?php echo Url::to([$childInfo['url']]) ?>"><i class=""></i><span><?= $childInfo['name']; ?></span></a></li>
                                                            <?php
                                                        endforeach;
                                                        ?>
                                                </ul>
                                                <?php
                                            endif;
                                            ?>
                                        </li>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>        
                        </div>
                    </div><!-- tab-content -->

                </div><!-- leftpanelinner -->
            </div><!-- leftpanel -->

            <div class="mainpanel">
                <div class="contentpanel">
                    <ol class="breadcrumb breadcrumb-quirk">
                        <li><a href="/"><i class="fa fa-home mr5"></i> 首页</a></li>
                        <?= $content ?>

<?php $this->endBody() ?>
                        <script type="text/javascript">jQuery(document).ready(function () {
                                jQuery('#page-modal').modal({"show": false});
                            });</script>
                        <script type="text/javascript">
                            $(function () {
                                //全选
                                $('#all_search').on('click', function () {
                                    if (this.checked) {
                                        $("input[name='ids']").prop("checked", true);
                                    } else {
                                        $("input[name='ids']").removeAttr("checked");
                                    }
                                });
                            });
                        </script>
                        </body>
                        </html>
<?php $this->endPage() ?>
