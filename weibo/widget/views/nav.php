<?php
$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<script type='text/javascript'>
    var delFollow = "{:U('Common:delFollow')}";
    var editStyle = "{:U('Common:editStyle')}";
    var getMsgUrl = "{:U('Common:getMsg')}";
</script>
</head>
<body>
    <!--==========顶部固定导行条==========-->
    <div id='top_wrap'>
        <div id="top">
            <div class='top_wrap'>
                <div class="logo fleft"></div>
                <ul class='top_left fleft'>
                    <li class='cur_bg'><a href='__APP__'>首页</a></li>
                    <li><a href="{:U('User/letter')}">私信</a></li>
                    <li><a href="{:U('User/comment')}">评论</a></li>
                    <li><a href="{:U('User/atme')}">@我</a></li>
                </ul>
                <div id="search" class='fleft'>
                    <form action='{:U("Search/sechUser")}' method='get'>
                        <input type='text' name='keyword' id='sech_text' class='fleft' value='搜索微博、找人'/>
                        <input type='submit' value='' id='sech_sub' class='fleft'/>
                    </form>
                </div>
                <div class="user fleft">
                    <a href="{:U('/' . session('uid'))}"><php>echo (M('userinfo')->where(array('uid' => session('uid')))->getField('username'));</php></a>
                </div>
                <ul class='top_right fleft'>
                    <li title='快速发微博' class='fast_send'><i class='icon icon-write'></i></li>
                    <li class='selector'><i class='icon icon-msg'></i>
                        <ul class='hidden'>
                            <li><a href="{:U('User/comment')}">查看评论</a></li>
                            <li><a href="{:U('User/letter')}">查看私信</a></li>
                            <li><a href="{:U('User/keep')}">查看收藏</a></li>
                            <li><a href="{:U('User/atme')}">查看@我</a></li>
                        </ul>
                    </li>
                    <li class='selector'><i class='icon icon-setup'></i>
                        <ul class='hidden'>
                            <li><a href="{:U('UserSetting/index')}">帐号设置</a></li>
                            <li><a href="" class='set_model'>模版设置</a></li>
                            <li><a href="{:U('Index/loginOut')}">退出登录</a></li>
                        </ul>
                    </li>
                    <!--信息推送-->
                    <li id='news' class='hidden'>
                        <i class='icon icon-news'></i>
                        <ul>
                            <li class='news_comment hidden'>
                                <a href="{:U('User/comment')}"></a>
                            </li>
                            <li class='news_letter hidden'>
                                <a href="{:U('User/letter')}"></a>
                            </li>
                            <li class='news_atme hidden'>
                                <a href="{:U('User/atme')}"></a>
                            </li>
                        </ul>
                    </li>
                    <!--信息推送-->
                </ul>
            </div>
        </div>
    </div>
    <!--==========顶部固定导行条==========-->
    <!--==========加关注弹出框==========-->

    <?php
//    $group = M('group')->where(array('uid' => session('uid')))->select();
    ?>
    <script type='text/javascript'>
        var addFollow = "{:U('Common/addFollow')}";
    </script>
    <div id='follow'>
        <div class="follow_head">
            <span class='follow_text fleft'>关注好友</span>
        </div>
        <div class='sel-group'>
            <span>好友分组：</span>
            <select name="gid">
                <option value="0">默认分组</option>
                <foreach name='group' item='v'>
                    <option value="{$v.id}">{$v.name}</option>
                </foreach>
            </select>
        </div>
        <div class='fl-btn-wrap'>
            <input type="hidden" name='follow'/>
            <span class='add-follow-sub'>关注</span>
            <span class='follow-cencle'>取消</span>
        </div>
    </div>
    <!--==========加关注弹出框==========-->

    <!--==========自定义模版==========-->
    <div id='model' class='hidden'>
        <div class="model_head">
            <span class="model_text">个性化设置</span>
            <span class="close fright"></span>
        </div>
        <ul>
            <li style='background:url(<?=$themeUrl; ?>/images/default.jpg) no-repeat;' theme='default'></li>
            <li style='background:url(<?=$themeUrl; ?>/images/style2.jpg) no-repeat;' theme='style2'></li>
            <li style='background:url(<?=$themeUrl; ?>/images/style3.jpg) no-repeat;' theme='style3'></li>
            <li style='background:url(<?=$themeUrl; ?>/images/style4.jpg) no-repeat;' theme='style4'></li>
        </ul>
        <div class='model_operat'>
            <span class='model_save'>保存</span>
            <span class='model_cancel'>取消</span>
        </div>
    </div>
    <!--==========自定义模版==========-->