<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\Areas;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="container block_box">
    <div class="breadcrumb"><span>您当前的位置：</span> <a href="/">首页</a> 》地址管理</div>
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
            <h4>地址管理</h4>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">已保存的有效地址</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="text-center">收货人</th>
                                <th>所在地区</th>
                                <th>街道地址</th>
                                <th class="text-center" style="width:100px;">联系方式</th>
                                <th class="text-center" style="width:150px;">操作</th>
                            </tr>
                            <?php
                            if ($addressList):
                                foreach ($addressList as $addressInfo):
                                    ?>

                                    <tr>
                                        <td class="text-center">
                                            <?php echo $addressInfo['accept_name']; ?>
                                        </td>
                                        <td class="">
                                            <?php echo join(' ', Areas::name($addressInfo['province'], $addressInfo['city'], $addressInfo['area'])); ?>
                                        </td>
                                        <td><?php echo $addressInfo['address']; ?></td>
                                        <td><?php echo $addressInfo['mobile']; ?></td>
                                        <td>
                                            <a href="<?php echo Url::to(['ucenter/order-detail', 'id' => $addressInfo['id']]); ?>">修改</a>|
                                            <a href="<?php echo Url::to(['ucenter/order-detail', 'id' => $addressInfo['id']]); ?>">删除</a>|
                                            <?php
                                            if (0 == $addressInfo['is_default']):
                                                ?>
                                                <a href="<?php echo Url::to(['ucenter/order-detail', 'id' => $addressInfo['id']]); ?>">设为默认</a>
                                                <?php
                                            else:
                                                ?>
                                                <a href="<?php echo Url::to(['ucenter/order-detail', 'id' => $addressInfo['id']]); ?>">取消默认</a>
                                            <?php
                                            endif;
                                            ?>
                                        </td>
                                    </tr>

                                    <?php
                                endforeach;
                            endif;
                            ?>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>