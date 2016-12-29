<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\widgets\LinkPager;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
//pprint($weiboInfo);
?>


<!--=====中部=====-->
<div id="middle" class='fleft'>
    <!--微博发布框-->
    <div class='send_wrap'>
        <div class='send_title fleft'></div>
        <div class='send_prompt fright'>
            <span>你还可以输入<span id='send_num'>140</span>个字</span>
        </div>
        <div class='send_write'>
            <form action='{:U("sendWeibo")}' method='post' name='weibo'>
                <textarea sign='weibo' name='content'></textarea>
                <span class='ta_right'></span>
                <div class='send_tool'>
                    <ul class='fleft'>
                        <li title='表情'><i class='icon icon-phiz phiz' sign='weibo'></i></li>
                        <li title='图片'><i class='icon icon-picture'></i>
                            <!--图片上传框-->
                            <div id="upload_img" class='hidden'>
                                <div class='upload-title'><p>本地上传</p><span class='close'></span></div>
                                <div class='upload-btn'>
                                    <input type="hidden" name='max' value=''/>
                                    <input type="hidden" name='medium' value=''/>
                                    <input type="hidden" name='mini' value=''/>
                                    <input type="file" name='picture' id='picture'/>
                                </div>
                            </div>
                            <!--图片上传框-->
                            <div id='pic-show' class='hidden'>
                                <img src="" alt=""/>
                            </div>
                        </li>
                    </ul>
                    <input type='submit' value='' class='send_btn fright' title='发布微博按钮'/>
                </div>
            </form>
        </div>
    </div>
    <!--微博发布框-->
    <div class='view_line'>
        <strong>微博</strong>
    </div>
    <?php
    if (empty($weiboInfo)):
        ?>
        没有发布的微博
        <?php
    else:
        foreach ($weiboInfo as $info):
//            pprint($info);
            if (!$info['isturn']):
                ?>
                <!--====================普通微博样式====================-->
                <div class="weibo">
                    <!--头像-->
                    <div class="face">
                        <a href="{:U('/' . $v['uid'])}">
                            <img src="
                                 <?php
                                 if(empty($info['face'])):
                                     ?>
                                 <?=$themeUrl?>/images/noface.gif
                                 <?php
                                 else:
                                     ?>
                                 __ROOT__/uploads/Face/{$v.face}
                                 <?php
                                 endif;
                                 ?>
                                 " width='50' height='50'/>
                        </a>
                    </div>
                    <div class="wb_cons">
                        <dl>
                            <!--用户名-->
                            <dt class='author'>
                                <a href="{:U('/' . $v['uid'])}"><?=$info['username']?></a>
                            </dt>
                            <!--发布内容-->
                            <dd class='content'>
                                <p><?php echo weiboCommon\utils\CommonTools::replace_weibo($info['content']);?></p>
                            </dd>
                            <!--微博图片-->
                            <?php
                            if($info['max']):
                                ?>
                                <dd>
                                    <div class='wb_img'>
                                        <!--小图-->
                                        <img src="/uploads/pic/<?=$info['mini']?>" class='mini_img'/>
                                        <div class="img_tool hidden">
                                            <ul>
                                                <li>
                                                    <i class='icon icon-packup'></i>
                                                    <span class='packup'>&nbsp;收起</span>
                                                </li>
                                                <li>|</li>
                                                <li>
                                                    <i class='icon icon-bigpic'></i>
                                                    <a href="/uploads/pic/<?=$info['max']?>" target='_blank'>&nbsp;查看大图</a>
                                                </li>
                                            </ul>
                                            <!--中图-->
                                            <div class="img_info"><img src="/uploads/pic/<?=$info['medium']?>"/></div>
                                        </div>
                                    </div>
                                </dd>
                            <?php
                            endif;
                            ?>
                        </dl>
                        <!--操作-->
                        <div class="wb_tool">
                            <!--发布时间-->
                            <span class="send_time"><?php echo weiboCommon\utils\CommonTools::time_format($info['time']);?></span>
                            <ul>
                                <if condition='isset($_SESSION["uid"]) && $_SESSION["uid"] eq $v["uid"]'>
                                    <li class='del-li hidden'><span class='del-weibo' wid='{$v.id}'>删除</span></li>
                                    <li class='del-li hidden'>|</li>
                                </if>
                                <li><span class='turn' id='<?=$info['id']?>'>转发<?php if($info["turn"]):?>(<?=$info['turn']?>)<?php endif;?></span></li>
                                <li>|</li>
                                <li class='keep-wrap'>
                                    <span class='keep' wid='<?=$info['id']?>'>收藏<?php if($info["keep"]):?>(<?=$info['keep']?>)<?php endif;?></span>
                                    <div class='keep-up hidden'></div>
                                </li>
                                <li>|</li>
                                <li><span class='comment' wid='<?=$info['id']?>'>评论<?php if($info["comment"]):?>(<?=$info['comment']?>)<?php endif;?></span></li>
                            </ul>
                        </div>
                        <!--=====回复框=====-->
                        <div class='comment_load hidden'>
                            <img src="__PUBLIC__/Images/loading.gif">评论加载中，请稍候...
                        </div>
                        <div class='comment_list hidden'>
                            <textarea name="" sign='comment{$key}'></textarea>
                            <ul>
                                <li class='phiz fleft' sign='comment{$key}'></li>
                                <li class='comment_turn fleft'>
                                    <label>
                                        <input type="checkbox" name=''/>同时转发到我的微博
                                    </label>
                                </li>
                                <li class='comment_btn fright' wid='{$v.id}' uid='{$v.uid}'>评论</li>
                            </ul>
                        </div>
                        <!--=====回复框结束=====-->
                    </div>
                </div>
                <?php
            else:
                ?>
                <!--====================转发样式====================-->
                <div class="weibo">
                    <!--头像-->
                    <div class="face">
                        <a href="{:U('/' . $v['uid'])}">
                            <img src="
                                 <?php
                                 if(empty($info['face'])):
                                     ?>
                                 <?=$themeUrl?>/images/noface.gif
                                 <?php
                                 else:
                                     ?>
                                 __ROOT__/uploads/Face/{$v.face}
                                 <?php
                                 endif;
                                 ?>
                            " width='50' height='50'/>
                        </a>
                    </div>
                    <div class="wb_cons">
                        <dl>
                            <!--用户名-->
                            <dt class='author'>
                                <a href="{:U('/' . $v['uid'])}"><?=$info['username']?></a>
                            </dt>
                            <!--发布内容-->
                            <dd class='content'>
                                <p>{$v.content|str_replace='//', '<span style="color:#ccc;font-weight:bold;">&nbsp;//&nbsp;</span>', ###|replace_weibo=###}</p>
                            </dd>
                            <!--转发的微博内容-->
                            <if condition='$v["isturn"] eq -1'>
                                <dd class="wb_turn">该微博已被删除</dd>
                                <else/>
                                <dd>
                                    <div class="wb_turn">
                                        <dl>
                                            <!--原作者-->
                                            <dt class='turn_name'>
                                                <a href="{:U('/' . $v['isturn']['uid'])}">@{$v.isturn.username}</a>
                                            </dt>
                                            <!--原微博内容-->
                                            <dd class='turn_cons'>
                                                <p>{$v.isturn.content|replace_weibo=###}</p>
                                            </dd>
                                            <!--原微博图片-->
                                            <if condition='$v["isturn"]["max"]'>
                                                <dd>
                                                    <div class="turn_img">
                                                        <!--小图-->
                                                        <img src="__ROOT__/Uploads/Pic/{$v.isturn.mini}" class='turn_mini_img'/>
                                                        <div class="turn_img_tool hidden">
                                                            <ul>
                                                                <li>
                                                                    <i class='icon icon-packup'></i>
                                                                    <span class='packup'>&nbsp;收起</span></li>
                                                                <li>|</li>
                                                                <li>
                                                                    <i class='icon icon-bigpic'></i>
                                                                    <a href="__ROOT__/Uploads/Pic/{$v.isturn.max}" target='_blank'>&nbsp;查看大图</a>
                                                                </li>
                                                            </ul>
                                                            <!--中图-->
                                                            <div class="turn_img_info">
                                                                <img src="__ROOT__/Uploads/Pic/{$v.isturn.medium}"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </dd>
                                            </if>
                                        </dl>
                                        <!--转发微博操作-->
                                        <div class="turn_tool">
                                            <span class='send_time'>
                                                {$v.isturn.time|time_format=###}
                                            </span>
                                            <ul>
                                                <li><a href="">转发<if condition='$v["isturn"]["turn"]'>({$v.isturn.turn})</if></a></li>
                                                <li>|</li>
                                                <li><a href="">评论<if condition='$v["isturn"]["comment"]'>({$v.isturn.comment})</if></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </dd>
                            </if>
                        </dl>
                        <!--操作-->
                        <div class="wb_tool">
                            <!--发布时间-->
                            <span class="send_time">
                                {$v.time|time_format=###}
                            </span>
                            <ul>
                                <if condition='isset($_SESSION["uid"]) && $_SESSION["uid"] eq $v["uid"]'>
                                    <li class='del-li hidden'><span class='del-weibo' wid='{$v.id}'>删除</span></li>
                                    <li class='del-li hidden'>|</li>
                                </if>
                                <li><span class='turn' id='{$v.id}' tid='{$v.isturn.id}'>转发<if condition='$v["turn"]'>({$v.turn})</if></span></li>
                                <li>|</li>
                                <li class='keep-wrap'>
                                    <span class='keep' wid='{$v.id}'>收藏<if condition='$v["keep"]'>({$v.keep})</if></span>
                                    <div class='keep-up hidden'></div>
                                </li>
                                <li>|</li>
                                <li><span class='comment' wid='{$v.id}'>评论<if condition='$v["comment"]'>({$v.comment})</if></span></li>
                            </ul>
                        </div>
                        <!--回复框-->
                        <div class='comment_load hidden'>
                            <img src="__PUBLIC__/Images/loading.gif">评论加载中，请稍候...
                        </div>
                        <div class='comment_list hidden'>
                            <textarea name="" sign='comment{$key}'></textarea>
                            <ul>
                                <li class='phiz fleft' sign='comment{$key}'></li>
                                <li class='comment_turn fleft'>
                                    <label>
                                        <input type="checkbox" name=''/>同时转发到我的微博
                                    </label>
                                </li>
                                <li class='comment_btn fright' wid='{$v.id}' uid='{$v.uid}'>评论</li>
                            </ul>
                        </div>
                        <!--回复框结束-->
                    </div>
                </div>
                <!--====================转发样式结束====================-->
            <?php
            endif;
        endforeach;
    endif;
    ?>
    <div id='page'>
        <?=
        LinkPager::widget([
            'pagination' => $pages,
            'firstPageLabel' => "First",
            'prevPageLabel' => 'Prev',
            'nextPageLabel' => 'Next',
            'lastPageLabel' => 'Last',
            'options' => ['class' => 'pagination'],
        ]);
        ?>
    </div>
</div>