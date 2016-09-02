<?php

use yii\helpers\Url;
use yii\helpers\Json;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use common\models\Areas;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<link href="<?php echo $themeUrl; ?>/libs/autovalidate/style.css" rel="stylesheet">
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

                                    <tr id="addressItem<?php echo $addressInfo['id']; ?>">
                                        <td class="text-center">
                                            <?php echo $addressInfo['accept_name']; ?>
                                        </td>
                                        <td class="">
                                            <?php echo join(' ', Areas::name($addressInfo['province'], $addressInfo['city'], $addressInfo['area'])); ?>
                                        </td>
                                        <td><?php echo $addressInfo['address']; ?></td>
                                        <td><?php echo $addressInfo['mobile']; ?></td>
                                        <td>
                                            <?php
                                            if (1 == $addressInfo['is_default']):
                                                $addressInfo['is_default'] = '1';
                                            else:
                                                $addressInfo['is_default'] = '0';
                                            endif;
                                            ?>
                                            <a href='javascript:void(0)' onclick='form_back(<?php echo Json::htmlEncode($addressInfo); ?>)'>修改</a>|
                                            <a href="javascript:void(0);" onclick="addressDel(<?php echo $addressInfo['id']; ?>);">删除</a>|
                                            <?php
                                            if (0 == $addressInfo['is_default']):
                                                ?>
                                                <a href="<?php echo Url::to(['ucenter/address-default', 'id' => $addressInfo['id'], 'is_default' => 1]); ?>">设为默认</a>
                                                <?php
                                            else:
                                                ?>
                                                <a href="<?php echo Url::to(['ucenter/address-default', 'id' => $addressInfo['id'], 'is_default' => 0]); ?>">取消默认</a>
                                                <?php
                                            endif;
                                            ?>
                                        </td>
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

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">收货地址</h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'info-form', 'options' => ['novalidate' => 'true', 'name' => 'form'], 'action' => Url::to(['/ucenter/address-edit'])]); ?>
                    <input type="hidden" name="id" />
                    <table class="table table-bordered ucenter_user_info">
                        <tbody>
                            <tr>
                                <th class="text-right" style="width:100px;">收货人姓名：</th>
                                <td>
                                    <input type="text" alt="填写真实的姓名" pattern="required" value="" name="accept_name" class="form-control">
                                    <label> 填写真实的姓名</label>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">所在地区：</th>
                                <td>
                                    <select class="form-control" name="province" child="city,area" onchange="areaChangeCallback(this);"></select>
                                    <label> 填写正确的区域地址</label><br/>
                                    <select class="form-control" name="city" child="area" parent="province" onchange="areaChangeCallback(this);"></select><br/>
                                    <select class="form-control" name="area" parent="city"></select>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">联系地址：</th>
                                <td>
                                    <input type="text" alt="填写正确的联系地址" pattern="required" value="" name="address" class="form-control">
                                    <label> 填写正确的联系地址</label>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">联系方式：</th>
                                <td>
                                    <input type="text" alt="填写正确的手机格式" pattern="mobi" value="" name="mobile" class="form-control">
                                    <label> 填写正确的手机格式</label>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th class="text-right">设为默认：</th>
                                <td>
                                    <input type="checkbox" name="is_default" value="1" class="">
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td>
                                    <button class="btn btn-lg btn-danger" id="embed-submit" type="submit"><i class="glyphicon glyphicon-floppy-disk"></i> 保 存</button>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/form.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/autovalidate/validate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate-plugin.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/area_select.js"></script>
<script type="text/javascript">
//DOM加载完毕
    $(function () {
        //初始化地域联动
        template.compile("areaTemplate", areaTemplate);
        createAreaSelect('province', 0, "");
    });
    //修改地址
    function form_back(obj)
    {
        //自动填充表单
        var form = new Form('form');
        form.init(obj);

        createAreaSelect('province', 0, obj.province);
        createAreaSelect('city', obj.province, obj.city);
        createAreaSelect('area', obj.city, obj.area);
    }

    /**
     * 生成地域js联动下拉框
     * @param name
     * @param parent_id
     * @param select_id
     */
    function createAreaSelect(name, parent_id, select_id)
    {
        //生成地区
        $.getJSON("<?php echo Url::to(['/common/area-child']); ?>", {"aid": parent_id, "random": Math.random()}, function (json)
        {
            $('[name="' + name + '"]').html(template.render('areaTemplate', {"select_id": select_id, "data": json}));
        });
    }
    
    /**
     * 删除地址
     * @param {type} addressId
     * @returns {undefined}
     */
    function addressDel(addressId) {
        $.getJSON('<?php echo Url::to(['/ucenter/address-del']); ?>', {id: addressId}, function (content) {
            if (content.result === true)
            {
                $('#addressItem' + addressId).remove();
            }
        });
    }
</script>

