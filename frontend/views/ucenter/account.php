<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="container block_box">
    <div class="breadcrumb"><span>您当前的位置：</span> <a href="/">首页</a> 》账户余额</div>
    <div class="ucenter_box">
        <div class="col-md-2 ucenter_menu">
            <div class="list-group">
                <?php
                if ($this->context->menuData):
                    foreach ($this->context->menuData as $key => $info):
                        ?>
                        <a href="<?php echo Url::to([$info['url']]); ?>" class="list-group-item <?php
                        if ($key == $this->context->currentMenu) {
                            echo 'active';
                        }
                        ?>"><?php echo $info['name']; ?></a>
                           <?php
                       endforeach;
                   endif;
                   ?>
                <!--
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="divider"></a>
                <a href="#" class="list-group-item">Link</a>
                -->
            </div>
        </div>
        <div class="col-md-10 ucenter_main">
            <h4>账户余额</h4>
            <table class="table member_info">
                <tbody>
                    <tr>
                        <td class="col-xs-4">账户余额：<?php echo $memberInfo['balance']; ?></td>
                        <td class="col-xs-4">在线充值</td>
                        <td class="col-xs-4">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-striped account_log">
                <thead>
                    <tr>
                        <th class="log_time">时间</th>
                        <th>存入金额</th>
                        <th>支出金额</th>
                        <th>当前金额</th>
                        <th>备注</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($logList):
                        foreach ($logList as $info):
                            ?>
                            <tr class="text-center">
                                <td><?php echo $info['time']; ?></td>
                                <td><?php echo $info['amount'] > 0 ? $info['amount'] . '元' : ''; ?></td>
                                <td><?php echo $info['amount'] < 0 ? $info['amount'] . '元' : ''; ?></td>
                                <td><?php echo $info['amount_log']; ?>元</td>
                                <td class="text-left"><?php echo $info['note']; ?></td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
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
</div>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/jquery.min.js"></script>