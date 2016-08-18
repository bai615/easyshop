<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

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
<!--
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->adminname . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
-->
<header>
    <div class="headerpanel">
        <div class="logopanel">
            <h2><a href="#">管理系统</a></h2>
        </div><!-- logopanel -->

        <div class="headerbar">
            <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>

            <div class="searchpanel">
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
                        <div id="noticePanel" class="btn-group">
                            <button class="btn btn-notice alert-notice" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-commenting"></i>
                            </button>
                            <div id="noticeDropdown" class="dropdown-menu dm-notice pull-right">
                                <div role="tabpanel">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-justified" role="tablist">
                                        <li class="active"><a data-target="#notification" data-toggle="tab">消息（2）</a></li>
                                        <li><a data-target="#reminders" data-toggle="tab">提醒（4）</a></li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="notification">
                                            <ul class="list-group notice-list">
                                                <li class="list-group-item unread">
                                                    <div class="row">
                                                        <div class="col-xs-2">
                                                            <i class="fa fa-envelope"></i>
                                                        </div>
                                                        <div class="col-xs-10">
                                                            <h5><a href="#">消息来自某好友</a></h5>
                                                            <small>2015-12-27</small>
                                                            <span>这是是摘要...</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <a class="btn-more" href="">查看全部消息 <i class="fa fa-long-arrow-right"></i></a>
                                        </div><!-- tab-pane -->

                                        <div role="tabpanel" class="tab-pane" id="reminders">
                                            <h1 id="todayDay" class="today-day"></h1>
                                            <h3 id="todayDate" class="today-date"></h3>
                                            <h4 class="panel-title">即将到期</h4>
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-xs-2">
                                                            <h4>20</h4>
                                                            <p>Aug</p>
                                                        </div>
                                                        <div class="col-xs-10">
                                                            <h5><a href="">HTML5/CSS3 Live! United States</a></h5>
                                                            <small>San Francisco, CA</small>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <a class="btn-more" href="">查看更多提醒 <i class="fa fa-long-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="btn-group">
                            <button type="button" class="btn btn-logged" data-toggle="dropdown">
                                <img src="/themes/quirk/images/avatar/small.jpg" alt="头像">
                                test                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="/admin/user/change-password"><i class="fa fa-cog"></i> 修改密码</a></li>
                                <li><a href="/site/logout" data-method="post" ><i class="fa fa-sign-out"></i> 退出</a></li>
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
                    <h4 class="media-heading">test<a data-toggle="collapse" data-target="#loguserinfo" class="pull-right"><i class="fa fa-angle-down"></i></a></h4>
                    <span>管理员</span>
                </div>
            </div><!-- leftpanel-profile -->

            <div class="leftpanel-userinfo collapse" id="loguserinfo">
                <h5 class="sidebar-title">地址</h5>
                <address>浙江省杭州市滨江区</address>
                <h5 class="sidebar-title">联系方式</h5>
                <ul class="list-group">
                    <li class="list-group-item">
                        <label class="pull-left">邮箱</label>
                        <span class="pull-right">me@themepixels.com</span>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left">电话</label>
                        <span class="pull-right">(032) 1234 567</span>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left">手机</label>
                        <span class="pull-right">+63012 3456 789</span>
                    </li>
                    <li class="list-group-item">
                        <label class="pull-left">第三方</label>
                        <div class="social-icons pull-right">
                            <a href="#"><i class="fa fa-facebook-official"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </li>
                </ul>
            </div><!-- leftpanel-userinfo -->
            <div class="tab-content">

                <div class="tab-pane active" id="mainmenu">
                    <h5 class="sidebar-title">菜单</h5>
                    <!-- sidebar组件 -->
                    <ul class="nav nav-pills nav-stacked nav-quirk"><li class="active"><a href="/site/index"><i class="fa fa-dashboard"></i><span>仪表盘</span></a></li>
                        <li class="nav-parent"><a href="/site/index"><i class="fa fa-cogs"></i><span>系统管理</span></a>
                            <ul class="children">
                                <li><a href="/admin/role/index"><i class=""></i><span>角色管理</span></a></li>
                                <li><a href="/admin/permission/index"><i class=""></i><span>权限管理</span></a></li>
                                <li><a href="/admin/rule/index"><i class=""></i><span>规则管理</span></a></li>
                                <li><a href="/admin/route/index"><i class=""></i><span>路由管理</span></a></li>
                                <li><a href="/admin/assignment/index"><i class=""></i><span>分配权限</span></a></li>
                                <li><a href="/admin/menu/index"><i class=""></i><span>菜单管理</span></a></li>
                            </ul>
                        </li>
                        <li class="nav-parent"><a href="/site/index"><i class="fa fa-calendar-minus-o"></i><span>扩展组件</span></a>
                            <ul class="children">
                                <li><a href="/plugin/index"><i class=""></i><span>组件管理</span></a></li>
                            </ul>
                        </li>
                        <li class="nav-parent"><a href="/site/index"><i class="fa fa-server"></i><span>内容管理</span></a>
                            <ul class="children">
                                <li><a href="/cat/index"><i class=""></i><span>分类管理</span></a></li>
                                <li><a href="/post/index"><i class=""></i><span>文章管理</span></a></li>
                            </ul>
                        </li></ul>        </div>
            </div><!-- tab-content -->

        </div><!-- leftpanelinner -->
    </div><!-- leftpanel -->

    <div class="mainpanel">
        <div class="contentpanel">
            <ol class="breadcrumb breadcrumb-quirk"><li><a href="/"><i class="fa fa-home mr5"></i> 首页</a></li>
                <li class="active">仪表盘</li>
            </ol>                
            <hr class="darken"> 
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'options'=>['class' => 'breadcrumb breadcrumb-quirk']
            ]) ?>
            <?= Alert::widget() ?>
        <?= $content ?>
<!--
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
-->
<?php $this->endBody() ?>
<script type="text/javascript">jQuery(document).ready(function () {
        jQuery('#page-modal').modal({"show": false});
    });</script>
</body>
</html>
<?php $this->endPage() ?>
