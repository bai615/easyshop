
<li class="active">后台首页</li>
</ol>                
<hr class="darken"> 
<div class="panel">
    <div class="panel-body">
        <div class="col-sm-6">
            <table class="table table-bordered table-primary nomargin">
                <thead>
                    <tr>
                        <th colspan="2">系统信息</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width:150px;">服务器软件</td>
                        <td><?php echo isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : ""; ?></td>
                    </tr>
                    <tr>
                        <td>服务器系统及 PHP</td>
                        <td><?php echo PHP_OS . ' / PHP v' . PHP_VERSION; ?></td>
                    </tr>
                    <tr>
                        <td>MySQL版本</td>
                        <td>
                            <?php
                            $versionInfo = Yii::$app->getDb()->createCommand("SELECT VERSION() as version")->queryOne();
                            echo $versionInfo['version'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>附件上传容量</td>
                        <td><?php echo \common\utils\CommonTools::getMaxSize(); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            <table class="table table-bordered table-primary nomargin">
                <thead>
                    <tr>
                        <th colspan="2">订单号</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width:150px;">注册用户</td>
                        <td>
                            <?php
                            echo \common\models\User::find()->where('is_del != 1')->count();
                            ?>
                            个
                        </td>
                    </tr>
                    <tr>
                        <td>产品数量</td>
                        <td>
                            <?php
                            echo \common\models\Goods::find()->where('is_del != 1')->count();
                            ?>
                            个
                        </td>
                    </tr>
                    <tr>
                        <td>品牌数量</td>
                        <td>
                            <?php
                            echo \common\models\Brand::find()->count();
                            ?>
                            个
                        </td>
                    </tr>
                    <tr>
                        <td>订单数量</td>
                        <td>
                            <?php
                            echo \common\models\Order::find()->where('is_del != 1')->count();
                            ?>
                            个
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading nopaddingbottom">
        <h4 class="panel-title">最新10条订单信息</h4>
    </div>
    <div class="panel-body">
        <hr>
        <div class="col-sm-12">
            <table class="table table-bordered table-primary nomargin">
                <thead>
                    <tr>
                        <th>订单号</th>
                        <th class="text-center">收货人</th>
                        <th class="text-center">支付状态</th>
                        <th class="text-center">发货状态</th>
                        <th class="text-center">支付方式</th>
                        <th class="text-center">用户名</th>
                        <th class="text-center">下单时间</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $orderInfo = common\models\Order::find()->where('is_del=0')->orderBy('id desc')->limit(10)->all();
                    if ($orderInfo):
                        foreach ($orderInfo as $info):
                            ?>
                            <tr>
                                <td>
                                    <?php echo $info['order_no']; ?>
                                </td>
                                <td class="text-center">
                                   <?php echo $info['accept_name']; ?>
                                </td>
                                <td class="text-center"><?php echo \common\models\Order::getOrderPayStatusText($info); ?></td>
                                <td class="text-center"><?php echo \common\models\Order::getOrderDistributionStatusText($info); ?></td>
                                <td class="text-center"><?php echo \common\models\Payment::getPaymentById($info['pay_type'],'name'); ?></td>
                                <td class="text-center"><?php echo \common\models\User::getNameById($info['user_id']); ?></td>
                                <td class="text-center"><?php echo $info['create_time']; ?></td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>