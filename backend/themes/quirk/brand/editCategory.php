
<li class="active">品牌分类</li>
<li class="active">编辑分类</li>
</ol>                
<hr class="darken"> 

<?php

use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use common\models\Category;
use backend\logics\GoodsLogic;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>

<link type="text/css" href="<?php echo $themeUrl; ?>/libs/artdialog/skins/aero.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/jquery/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/artDialog.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artdialog/plugins/iframeTools.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/artTemplate/artTemplate-plugin.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/libs/autovalidate/validate.js"></script>
<script type="text/javascript" src="<?php echo $themeUrl; ?>/js/form.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $themeUrl; ?>/libs/autovalidate/style.css" />

<div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading nopaddingbottom">
                <h4 class="panel-title">分类信息</h4>
            </div>
            <div class="panel-body">
                <hr>
                <?php $form = ActiveForm::begin(['id' => 'basicForm', 'options' => ['name' => 'categoryForm', 'class' => 'form-horizontal'], 'action' => Url::to(['/brand/category-save'])]); ?>
                <input type="hidden" name="id" value="<?php
                if (isset($categoryInfo)) {
                    echo $categoryInfo['id'];
                }
                ?>" />
                <input type='hidden' name="callback" value="" />
                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger">*</span> 分类名称：</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" pattern="required" alt="分类名称不能为空" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">所属商品分类： <span class="text-danger"></span></label>
                    <div class="col-sm-9">
                        <span id="__categoryBox" style="margin-bottom:8px"></span>
                        <button class="btn btn-success" type="button" name="_goodsCategoryButton"><span class="add">设置分类</span></button>
                    </div>
                </div>

                <!-- -->
                <script id="categoryButtonTemplate" type="text/html">
                    <ctrlArea>
                        <input type="hidden" value="<%= templateData['id'] %>" name="goods_category_id" />
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
                            art.dialog.data('categoryWhole',<?php echo Json::encode((Category::getAll())); ?>);
                            art.dialog.data('categoryParentData', <?php echo Json::encode(GoodsLogic::categoryParentStruct(Category::getAll())); ?>);
                            
                        });
                        
                        /**
                         * @brief 商品分类弹出框
                         * @param string urlValue 提交地址
                         * @param string categoryName 商品分类name值
                         */
                        function selectGoodsCategory()
                        {
                            //根据表单里面的name值生成分类ID数据
                            var categoryName = "goods_category_id";
                            var result = [];
                            $('[name="' + categoryName + '"]').each(function ()
                            {
                                result.push(this.value);
                            });
                            art.dialog.data('categoryValue', result);
                            
                            //URL地址
                            var urlValue = "<?php echo Url::to(['/goods/goods-category', 'type' => 'radio']); ?>";
                            
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
                        
                        <?php if (isset($categoryInfo['goods_category_id']) && 0 != ($categoryInfo['goods_category_id'])): ?>
                            //如果商品分类信息存在，进行初始化信息
                            var categoryJson = '<?php echo Json::encode([Category::getOneInfo($categoryInfo['goods_category_id'])]); ?>';
                            createGoodsCategory(JSON.parse(categoryJson));
                        <?php endif; ?>
                        
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

                    <div class="row">
                        <div class="col-sm-9 col-sm-offset-3">
                            <button class="btn btn-success btn-quirk btn-wide mr5">保存</button>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
<?php if(isset($categoryInfo)):?>
    <script type="text/javascript">
        $(function ()
        {
            var formObj = new Form('categoryForm');
            var data = <?php echo Json::encode(ArrayHelper::toArray($categoryInfo)); ?>;
            formObj.init(data);
        });
    </script>
<?php endif;?>    