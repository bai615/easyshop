<?php

use yii\helpers\Url;
use yii\helpers\Json;
use yii\bootstrap\ActiveForm;
use common\models\Goods;
use common\models\Model;
use common\models\Brand;
use common\models\Category;
use backend\logics\GoodsLogic;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<li class="active">商品管理</li>
<li class="active">添加商品</li>
</ol>                
<hr class="darken"> 

<link type="text/css" href="<?php echo $themeUrl; ?>/libs/artdialog/skins/aero.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/artDialog.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate-plugin.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/autovalidate/validate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/form.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $themeUrl; ?>/libs/autovalidate/style.css" />
<style type="text/css">
    #goodsBaseHead th{text-align: center;}
    #propert_table label.attr {color: #1d1d1d;margin-right: 10px;}
    #thumbnails .pic {
        float: left;
        margin-right: 10px;
        text-align: center;
    }
    #thumbnails .pic img {
        border: 3px solid #efefed;
        cursor: pointer;
    }
    #thumbnails .pic img.current {
        border: 3px solid #f60;
    }

</style>
<div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading nopaddingbottom">
                <h4 class="panel-title">商品信息</h4>
            </div>
            <div class="panel-body">
                <hr>
                <?php $form = ActiveForm::begin(['id' => 'basicForm', 'options' => ['name' => 'goodsForm', 'class' => 'form-horizontal'], 'action' => Url::to(['/goods/update'])]); ?>
                <input type="hidden" name="id" value="<?php if (isset($form_info)){echo $form_info['id'];} ?>" />
                <input type='hidden' name="img" value="" />
                <input type='hidden' name="_imgList" value="" />
                <input type='hidden' name="callback" value="" />
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger">*</span> 商品名称：</label>
                    <div class="col-sm-9">
                        <!--<input type="text" name="name" class="form-control" placeholder="请填写商品名称..." required />-->
                        <input type="text" name="name" class="form-control" pattern="required" alt="商品名称不能为空" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">所属分类： <span class="text-danger"></span></label>
                    <div class="col-sm-9">
                        <div id="__categoryBox" style="margin-bottom:8px"></div>
                        <button class="btn btn-success" type="button" name="_goodsCategoryButton"><span class="add">设置分类</span></button>
                    </div>
                </div>
                
                <!-- -->
                <script id="categoryButtonTemplate" type="text/html">
                <ctrlArea>
                    <input type="hidden" value="<%= templateData['id'] %>" name="_goods_category[]" />
                    <button class="btn" type="button" onclick="return confirm('确定删除此分类？') ? $(this).parent().remove() : '';">
                        <span><%= templateData['name'] %></span>
                    </button>
                </ctrlArea>
                </script>
                <script type="text/javascript">

                    //插件value预设值
                    jQuery(function ()
                    {
                        //绑定UI按钮入口
                        $(document).on("click", "[name='_goodsCategoryButton']", selectGoodsCategory);

                        //完整分类数据
                        art.dialog.data('categoryWhole',<?php echo Json::encode((Category::getAll()));?>);
                        art.dialog.data('categoryParentData', <?php echo Json::encode(GoodsLogic::categoryParentStruct(Category::getAll()));?>);

                    });

                    /**
                     * @brief 商品分类弹出框
                     * @param string urlValue 提交地址
                     * @param string categoryName 商品分类name值
                     */
                    function selectGoodsCategory()
                    {
                        //根据表单里面的name值生成分类ID数据
                        var categoryName = "_goods_category[]";
                        var result = [];
                        $('[name="' + categoryName + '"]').each(function ()
                        {
                            result.push(this.value);
                        });
                        art.dialog.data('categoryValue', result);

                        //URL地址
                        var urlValue = "<?php echo Url::to(['/goods/goods-category', 'type' => 'checkbox']); ?>";

                        art.dialog.open(urlValue, {
                            title: '选择商品分类',
                            okVal: '确定',
                            ok: function (iframeWin, topWin)
                            {
                                var categoryObject = [];
                                var categoryWhole = art.dialog.data('categoryWhole');
                                var categoryValue = art.dialog.data('categoryValue');
                                for (var item in categoryWhole)
                                {
                                    item = categoryWhole[item];
                                    if (jQuery.inArray(item['id'], categoryValue) != -1)
                                    {
                                        categoryObject.push(item);
                                    }
                                }
                                createGoodsCategory(categoryObject);
                            },
                            cancel: function ()
                            {
                                return true;
                            }
                        })
                    }

                    <?php if(isset($goods_category)):?>
                    //如果商品分类信息存在，进行初始化信息
                    var categoryJson = '<?php echo Json::encode($goods_category);?>';
                    createGoodsCategory(JSON.parse(categoryJson));
                    <?php endif;?>

                    //生成商品分类
                    function createGoodsCategory(categoryObj)
                    {
                        if (!categoryObj)
                        {
                            return;
                        }

                        $('#__categoryBox').empty();
                        for (var item in categoryObj)
                        {
                            item = categoryObj[item];
                            var goodsCategoryHtml = template.render('categoryButtonTemplate', {'templateData': item});
                            $('#__categoryBox').append(goodsCategoryHtml);
                        }
                    }
                </script>
                <!-- -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">是否上架：</label>
                    <div class="col-sm-8">
                        <label class="checkbox-inline"> 
                            <input type="radio" name="is_del" id="is_del_0" value="0"> 是
                        </label>
                        <label class="checkbox-inline"> 
                            <input type="radio" name="is_del" id="is_del_2" value="2" checked> 否
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">计量单位： <span class="text-danger"></span></label>
                    <div class="col-sm-9">
                        <input type="text" name="unit" class="form-control" value="千克" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">基本数据： <span class="text-danger"></span></label>
                    <div class="col-sm-9">
                        <table class="table table-bordered">
                            <thead id="goodsBaseHead"></thead>

                            <!--商品标题模板-->
                            <script id="goodsHeadTemplate" type='text/html'>
                                <tr>
                                    <th>商品货号</th>
                                    <%var isProduct = false;%>
                                    <%for(var item in templateData){%>
                                    <%isProduct = true;%>
                                    <th><a href="javascript:confirm('确定要删除此列规格？','delSpec(<%=templateData[item]['id']%>)');"><%=templateData[item]['name']%>【删】</a></th>
                                    <%}%>
                                    <th>库存</th>
                                    <th>市场价格</th>
                                    <th>销售价格</th>
                                    <th>成本价格</th>
                                    <th>重量</th>
                                    <%if(isProduct == true){%>
                                    <th>操作</th>
                                    <%}%>
                                </tr>
                            </script>

                            <tbody id="goodsBaseBody"></tbody>

                            <!--商品内容模板-->
                            <script id="goodsRowTemplate" type="text/html">
                                <%var i=0;%>
                                <%for(var item in templateData){%>
                                <%item = templateData[item]%>
                                <tr class='td_c'>
                                    <td><input class="form-control col-sm-2" name="_goods_no[<%=i%>]" pattern="required" type="text" value="<%=item['goods_no'] ? item['goods_no'] : item['products_no']%>" /></td>
                                    <%var isProduct = false;%>
                                    <%var specArrayList = parseJSON(item['spec_array'])%>
                                    <%for(var result in specArrayList){%>
                                    <%result = specArrayList[result]%>
                                <input type='hidden' name="_spec_array[<%=i%>][]" value='{"id":"<%=result.id%>","type":"<%=result.type%>","value":"<%=result.value%>","name":"<%=result.name%>"}' />
                                <%isProduct = true;%>
                                <td class="text-center">
                                    <%if(result['type'] == 1){%>
                                    <%=result['value']%>
                                    <%}else{%>
                                    <img class="img_border" width="30px" height="30px" src="<%=result['value']%>">
                                    <%}%>
                                </td>
                                <%}%>
                                <td><input class="form-control col-sm-2" name="_store_nums[<%=i%>]" type="text" pattern="int" value="<%=item['store_nums']?item['store_nums']:100%>" /></td>
                                <td><input class="form-control col-sm-2" name="_market_price[<%=i%>]" type="text" pattern="float" value="<%=item['market_price']%>" /></td>
                                <td>
                                    <input class="form-control col-sm-2" name="_sell_price[<%=i%>]" type="text" pattern="float" value="<%=item['sell_price']%>" />
                                </td>
                                <td><input class="form-control col-sm-2" name="_cost_price[<%=i%>]" type="text" pattern="float" empty value="<%=item['cost_price']%>" /></td>
                                <td><input class="form-control col-sm-2" name="_weight[<%=i%>]" type="text" pattern="float" empty value="<%=item['weight']%>" /></td>
                                <%if(isProduct == true){%>
                                <td><a href="javascript:void(0)" onclick="delProduct(this);"><img class="operator" src="<?php echo $themeUrl; ?>/images/icon_del.gif" alt="删除" /></a></td>
                                <%}%>
                                </tr>
                                <%i++;%>
                                <%}%>
                            </script>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 商品模型：</label>
                    <div class="col-sm-9">
                        <div class="col-sm-3">
                            <select class="form-control" name="model_id" onchange="create_attr(this.value)">
                                <option value="0">通用类型 </option>
                                <?php
                                $modelList = Model::getModelList();
                                if ($modelList):
                                    foreach ($modelList as $value):
                                        ?>
                                        <option value="<?= $value['id']; ?>"><?= $value['name']; ?></option>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-5" style="margin-top: 12px;">
                            <label>可以加入商品扩展属性，比如：型号，年代，款式...</label>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="properties" style="display:none">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 扩展属性：</label>
                    <div class="col-sm-8">
                        <table class="table table-bordered">
                            <tbody id="propert_table"></tbody>
                            <script type='text/html' id='propertiesTemplate'>
                                <%for(var item in templateData){%>
                                <%item = templateData[item]%>
                                <%var valueItems = item['value'].split(',');%>
                                <tr>
                                    <th><%=item["name"]%></th>
                                    <td>
                                        <%if(item['type'] == 1){%>
                                        <%for(var tempVal in valueItems){%>
                                        <%tempVal = valueItems[tempVal]%>
                                        <label class="attr"><input type="radio" name="attr_id_<%=item['id']%>" value="<%=tempVal%>" /><%=tempVal%></label>
                                        <%}%>
                                        <%}else if(item['type'] == 2){%>
                                        <%for(var tempVal in valueItems){%>
                                        <%tempVal = valueItems[tempVal]%>
                                        <label class="attr"><input type="checkbox" name="attr_id_<%=item['id']%>[]" value="<%=tempVal%>"/><%=tempVal%></label>
                                        <%}%>
                                        <%}else if(item['type'] == 3){%>
                                        <select class="auto" name="attr_id_<%=item['id']%>">
                                            <%for(var tempVal in valueItems){%>
                                            <%tempVal = valueItems[tempVal]%>
                                            <option value="<%=tempVal%>"><%=tempVal%></option>
                                            <%}%>
                                        </select>
                                        <%}else if(item['type'] == 4){%>
                                        <input type="text" name="attr_id_<%=item['id']%>" value="<%=item['value']%>" class="normal" />
                                        <%}%>
                                    </td>
                                </tr>
                                <%}%>
                            </script>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 规格：</label>
                    <div class="col-sm-8">
                        <button class="btn btn-success" type="button" name="" onclick="selSpec()"><span class="add">添加规格</span></button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 商品推荐类型：</label>
                    <div class="col-sm-8">
                        <label class="checkbox-inline"> 
                            <input name="_goods_commend[]" type="checkbox" value="3"/>热卖商品
                        </label>
                        <label class="checkbox-inline"> 
                            <input name="_goods_commend[]" type="checkbox" value="4">推荐商品
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 商品品牌：</label>
                    <div class="col-sm-8">
                        <div class="col-sm-3">
                            <select class="form-control" name="brand_id">
                                <option value="0">请选择</option>
                                <?php
                                $brandList = Brand::getAllList();
                                if ($brandList):
                                    foreach ($brandList as $value):
                                        ?>
                                        <option value="<?= $value['id']; ?>"><?= $value['name']; ?></option>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 产品相册：</label>
                    <div class="col-sm-9">
                        <div class="col-sm-3">
                            <input class="form-control" type="text" disabled />
                        </div>
                        <div class="col-sm-1">
                            <div class="btn btn-success upload_btn">
                                <span id="uploadButton">选择...</span>
                            </div>
                        </div>
                        <div class="col-sm-8" style="margin-top: 12px;">
                            <label>可以上传多张图片，分辨率3000px以下，大小不得超过{echo:IUpload::getMaxSize()}</label>
                        </div>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:0px;">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-8" id="divFileProgressContainer">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:0px;">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-9" id="thumbnails">
                    </div>
                    <!--图片模板-->
                    <script type='text/html' id='picTemplate'>
                        <span class='pic'>
                            <img onclick="defaultImage(this);" style="margin:5px; opacity:1;width:100px;height:100px" src="<?php echo Yii::$app->params['upload_url']; ?><%=picRoot%>" alt="<%=picRoot%>" /><br />
                            <a class='orange' href='javascript:void(0)' onclick="$(this).parent().remove();">删除</a>
                        </span>
                    </script>
                </div>

                <hr>

                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger"></span> 产品描述：</label>
                    <div class="col-sm-9">
                        <?php

                        use kucha\ueditor\UEditor;

echo UEditor::widget([
                            'name' => 'content',
                            'clientOptions' => [
                                //编辑区域大小
                                'initialFrameHeight' => '200',
                                //设置语言
                                'lang' => 'zh-cn', //中文为 zh-cn
                                //定制菜单
                                'toolbars' => [
                                    [
                                        'fullscreen', 'source', '|', 'undo', 'redo', '|',
                                        'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                                        'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                                        'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                                        'directionalityltr', 'directionalityrtl', 'indent', '|',
                                        'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                                        'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                                        'simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'map', 'pagebreak', 'template', 'background', '|',
                                        'horizontal', 'date', 'time', 'spechars', '|',
                                        'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
                                        'print', 'preview', 'searchreplace', 'help',
                                    ],
                                ]
                            ]
                        ]);
                        ?>
                        <?php /**
                          <textarea id="content" name="content" style="width:700px;height:400px;"></textarea>
                         */ ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button class="btn btn-success btn-quirk btn-wide mr5" onclick="return checkForm()">保存</button>
                        <!--<button type="reset" class="btn btn-quirk btn-wide btn-default">Reset</button>-->
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div><!-- panel-body -->
        </div><!-- panel -->

    </div><!-- col-md-6 -->
</div>

<script language="javascript">
    //创建表单实例
    var formObj = new Form('goodsForm');

    //默认货号
    var defaultProductNo = '<?php echo Goods::createGoodsNo(); ?>';

    $(function ()
    {
        initProductTable();

        //存在商品信息
        <?php if (isset($form_info)): ?>
                var goods = <?php echo Json::encode($form_info); ?>

                var goodsRowHtml = template.render('goodsRowTemplate',{'templateData':[goods]});
                $('#goodsBaseBody').html(goodsRowHtml);

                formObj.init(goods);

                //模型选择
                $('[name="model_id"]').change();

        <?php else: ?>
            $('[name="_goods_no[0]"]').val(defaultProductNo);
        <?php endif; ?>


        //存在货品信息,进行数据填充
        <?php if (isset($product)): ?>
            var spec_array = <?php echo $product[0]['spec_array']; ?>;
            var product = <?php echo Json::encode($product); ?>;

            var goodsHeadHtml = template.render('goodsHeadTemplate', {'templateData': spec_array});
            $('#goodsBaseHead').html(goodsHeadHtml);

            var goodsRowHtml = template.render('goodsRowTemplate', {'templateData': product});
            $('#goodsBaseBody').html(goodsRowHtml);
        <?php endif; ?>

        //商品促销回填
        <?php if (isset($goods_commend)): ?>
            formObj.setValue('_goods_commend[]', "<?php echo join(';', $goods_commend); ?>");
        <?php endif; ?>

            //商品图片的回填
        <?php if (isset($goods_photo)):?>
        var goodsPhoto = <?php echo Json::encode($goods_photo); ?>;
        for(var item in goodsPhoto)
        {
            var picHtml = template.render('picTemplate',{'picRoot':goodsPhoto[item].img});
            $('#thumbnails').append(picHtml);
        }
        <?php endif; ?>

            //商品默认图片
        <?php if (isset($form_info['img']) && $form_info['img']):?>
        $('#thumbnails img[alt="<?php echo $form_info['img'];?>"]').addClass('current');
        <?php endif; ?>
    });
    //初始化货品表格
    function initProductTable()
    {
        //默认产生一条商品标题空挡
        var goodsHeadHtml = template.render('goodsHeadTemplate', {'templateData': []});
        $('#goodsBaseHead').html(goodsHeadHtml);

        //默认产生一条商品空挡
        var goodsRowHtml = template.render('goodsRowTemplate', {'templateData': [[]]});
        $('#goodsBaseBody').html(goodsRowHtml);
    }
    //根据模型动态生成扩展属性
    function create_attr(model_id)
    {
        $.getJSON("<?php echo Url::to(['/goods/attribute-init']); ?>", {'model_id': model_id, 'random': Math.random()}, function (json)
        {
            if (json && json.length > 0)
            {
                var templateHtml = template.render('propertiesTemplate', {'templateData': json});
                $('#propert_table').html(templateHtml);
                $('#properties').show();

                //表单回填设置项
                <?php if(isset($goods_attr)):?>
                <?php $attrArray = array();?>
                <?php foreach($goods_attr as $key => $item):?>
                <?php $valArray = explode(',',$item);?>
                <?php $attrArray[] = '"attr_id_'.$key.'[]":"'.join(";",($valArray)).'"'?>
                <?php $attrArray[] = '"attr_id_'.$key.'":"'.join(";",($valArray)).'"'?>
                <?php endforeach;?>
                formObj.init({<?php echo join(',',$attrArray);?>});
                <?php endif;?>

            } else
            {
                $('#properties').hide();
            }
        });
    }

    //添加规格
    function selSpec()
    {
        //货品是否已经存在
        var tempUrl = $('input:hidden[name^="_spec_array"]').length > 0 ? '<?php echo Url::to(['/goods/search-spec', 'type' => 'checkbox']); ?>' : '<?php echo Url::to(['/goods/search-spec', 'model_id' => '@model_id@', 'goods_id' => '@goods_id@']); ?>';
        var model_id = $('[name="model_id"]').val();
        var goods_id = $('[name="id"]').val();

        tempUrl = tempUrl.replace('@model_id@', model_id);
        tempUrl = tempUrl.replace('@goods_id@', goods_id);

        art.dialog.open(tempUrl, {
            title: '设置商品的规格',
            okVal: '保存',
            ok: function (iframeWin, topWin)
            {
                //添加的规格
                var addSpecObject = $(iframeWin.document).find('[id^="vertical_"]');
                if (addSpecObject.length === 0)
                {
                    return;
                }

                var specIsHere = getIsHereSpec();
                var specValueData = specIsHere.specValueData;
                var specData = specIsHere.specData;

                //追加新建规格
                addSpecObject.each(function ()
                {
                    $(this).find('input:hidden[name="specJson"]').each(function ()
                    {
                        var json = $.parseJSON(this.value);
                        if (!specValueData[json.id])
                        {
                            specData[json.id] = json;
                            specValueData[json.id] = [];
                        }
                        specValueData[json.id].push(json['value']);
                    });
                });
                createProductList(specData, specValueData);
            }
        });
    }

    /**
     * @brief 根据规格数据生成货品序列
     * @param object specData规格数据对象
     * @param object specValueData 规格值对象集合
     */
    function createProductList(specData, specValueData)
    {
        //生成货品的笛卡尔积
        var specMaxData = descartes(specValueData, specData);

        //从表单中获取默认商品数据
        var productJson = {};
        $('#goodsBaseBody tr:first').find('input[type="text"]').each(function () {
            productJson[this.name.replace(/^_(\w+)\[\d+\]/g, "$1")] = this.value;
        });

        //生成最终的货品数据
        var productList = [];
        for (var i = 0; i < specMaxData.length; i++)
        {
            var productItem = {};
            for (var index in productJson)
            {
                //自动组建货品号
                if (index == 'goods_no')
                {
                    //值为空时设置默认货号
                    if (productJson[index] == '')
                    {
                        productJson[index] = defaultProductNo;
                    }

                    if (productJson[index].match(/(?:\-\d*)$/) == null)
                    {
                        //正常货号生成
                        productItem['goods_no'] = productJson[index] + '-' + (i + 1);
                    } else
                    {
                        //货号已经存在则替换
                        productItem['goods_no'] = productJson[index].replace(/(?:\-\d*)$/, '-' + (i + 1));
                    }
                } else
                {
                    productItem[index] = productJson[index];
                }
            }
            productItem['spec_array'] = specMaxData[i];
            productList.push(productItem);
        }

        //创建规格标题
        var goodsHeadHtml = template.render('goodsHeadTemplate', {'templateData': specData});
        $('#goodsBaseHead').html(goodsHeadHtml);

        //创建货品数据表格
        var goodsRowHtml = template.render('goodsRowTemplate', {'templateData': productList});
        $('#goodsBaseBody').html(goodsRowHtml);

        if ($('#goodsBaseBody tr').length == 0)
        {
            initProductTable();
        }
    }

    //获取已经存在的规格
    function getIsHereSpec()
    {
        //开始遍历规格
        var specValueData = {};
        var specData = {};

        //规格已经存在的数据
        if ($('input:hidden[name^="_spec_array"]').length > 0)
        {
            $('input:hidden[name^="_spec_array"]').each(function ()
            {
                var json = $.parseJSON(this.value);
                if (!specValueData[json.id])
                {
                    specData[json.id] = json;
                    specValueData[json.id] = [];
                }

                if (jQuery.inArray(json['value'], specValueData[json.id]) == -1)
                {
                    specValueData[json.id].push(json['value']);
                }
            });
        }
        return {"specData": specData, "specValueData": specValueData};
    }

    //笛卡儿积组合
    function descartes(list, specData)
    {
        //parent上一级索引;count指针计数
        var point = {};

        var result = [];
        var pIndex = null;
        var tempCount = 0;
        var temp = [];

        //根据参数列生成指针对象
        for (var index in list)
        {
            if (typeof list[index] == 'object')
            {
                point[index] = {'parent': pIndex, 'count': 0}
                pIndex = index;
            }
        }

        //单维度数据结构直接返回
        if (pIndex == null)
        {
            return list;
        }

        //动态生成笛卡尔积
        while (true)
        {
            for (var index in list)
            {
                tempCount = point[index]['count'];
                temp.push({"id": specData[index].id, "type": specData[index].type, "name": specData[index].name, "value": list[index][tempCount]});
            }

            //压入结果数组
            result.push(temp);
            temp = [];

            //检查指针最大值问题
            while (true)
            {
                if (point[index]['count'] + 1 >= list[index].length)
                {
                    point[index]['count'] = 0;
                    pIndex = point[index]['parent'];
                    if (pIndex == null)
                    {
                        return result;
                    }

                    //赋值parent进行再次检查
                    index = pIndex;
                } else
                {
                    point[index]['count']++;
                    break;
                }
            }
        }
    }

    //删除货品
    function delProduct(_self)
    {
        $(_self).parent().parent().remove();
        if ($('#goodsBaseBody tr').length === 0)
        {
            initProductTable();
        }
    }

    /**
     * 图片上传回调,handers.js回调
     * @param picJson => {'flag','img','list','show'}
     */
    function uploadPicCallback(picJson)
    {
        var picHtml = template.render('picTemplate', {'picRoot': picJson.img});
        $('#thumbnails').append(picHtml);

        //默认设置第一个为默认图片
        if ($('#thumbnails img[class="current"]').length == 0)
        {
            $('#thumbnails img:first').addClass('current');
        }
    }

    /**
     * 设置商品默认图片
     */
    function defaultImage(_self)
    {
        $('#thumbnails img').removeClass('current');
        $(_self).addClass('current');
    }

    //提交表单前的检查
    function checkForm() {
        //整理商品图片
        var goodsPhoto = [];
        $('#thumbnails img').each(function () {
            goodsPhoto.push(this.alt);
        });
        if (goodsPhoto.length > 0) {
            $('input[name="_imgList"]').val(goodsPhoto.join(','));
            $('input[name="img"]').val($('#thumbnails img[class="current"]').attr('alt'));
        }
        return true;
    }

    //删除规格
    function delSpec(specId)
    {
        $('input:hidden[name^="_spec_array"]').each(function()
        {
            var json = $.parseJSON(this.value);
            if(json.id == specId)
            {
                $(this).remove();
            }
        });

        //当前已经存在的规格数据
        var specIsHere = getIsHereSpec();
        createProductList(specIsHere.specData,specIsHere.specValueData);
    }
</script>
<?php

use backend\utils\Swfupload;

$swfloadObject = new Swfupload($themeUrl);
$swfloadObject->show();
?>
<?php
/**
  <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/ueditor/ueditor.config.js"></script>
  <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/ueditor/ueditor.all.min.js"></script>
  <script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/ueditor/lang/zh-cn/zh-cn.js"></script>
  <script type="text/javascript">
  UE.getEditor('content', {
  //这里可以选择自己需要的工具按钮名称
  serverUrl: '<?php // echo Yii::app()->createUrl('ueditor/index');?>',
  toolbars: [
  [
  'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
  'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
  'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
  'directionalityltr', 'directionalityrtl', 'indent', '|',
  'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
  'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
  'simpleupload', 'insertimage', 'emotion', 'scrawl', '|',
  'horizontal', 'date', 'time', 'spechars', '|',
  'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
  'preview', 'searchreplace'
  ]
  ],
  });
  UE.getEditor('content').addListener('ready', function (ue) {
  UE.getEditor('content').setHeight(480);
  });
  //$(function () {
  //    var ue = UE.getEditor('content');
  //});
  </script>
 */
?>