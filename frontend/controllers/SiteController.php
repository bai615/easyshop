<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Goods;
use common\models\CategoryExtend;
use common\models\GoodsPhotoRelation;
use common\models\Products;
use common\models\Favorite;
use common\models\Brand;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends BaseController {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * 前台首页
     */
    public function actionIndex() {
        $userId = $this->data['shopUserInfo']['userId'];
        //获取我的商品收藏
        $favoriteArr = array();
        if ($userId) {
            $favoriteArr = Favorite::getMyFavorite($userId);
        }
        return $this->render('index', ['favoriteArr' => $favoriteArr]);
    }

    /**
     * 商品详情
     */
    public function actionProducts() {
        $goodsId = Yii::$app->request->get('id');

        //使用商品id获得商品信息
        $goodsModel = new Goods();
        $goodsInfo = $goodsModel->find()
//            ->select(['id','name'])
            ->where('id=:goodsId and is_del=0', [':goodsId' => $goodsId])
            ->one();

        //品牌名称
        if ($goodsInfo['brand_id']) {
            $brandModel = new Brand();
            $brandInfo = $brandModel->find()
                ->select(['name'])
                ->where('id=:brandId', [':brandId' => $goodsInfo['brand_id']])
                ->one();
            if ($brandInfo) {
                $goodsInfo['brand_name'] = $brandInfo['name'];
            }
        }

        //获取商品分类
        $categoryInfo = CategoryExtend::getCategoryId($goodsId);
        $goodsInfo['category_id'] = empty($categoryInfo) ? 0 : $categoryInfo['id'];

        //获取商品图片
        $goodsPhotoList = GoodsPhotoRelation::getGoodsPhotoList($goodsId);
        if ($goodsPhotoList) {
            //格式化商品图片数据
            $goodsInfo['photo'] = GoodsPhotoRelation::formatGoodsPhotoList($goodsPhotoList, $goodsInfo['img']);
        }

        //获得商品的价格区间
        $productModel = new Products();
        $productList = $productModel->getProductList($goodsId);
        if ($productList) {
            $goodsInfo['price_area'] = $productList;
        }

        //获取我的商品收藏
        $userId = $this->data['shopUserInfo']['userId'];
        $favoriteArr = array();
        if ($userId) {
            $favoriteArr = Favorite::getMyFavorite($userId);
        }

        return $this->render('products', ['goodsInfo' => $goodsInfo, 'favoriteArr' => $favoriteArr]);
    }

    /**
     * 获取货品数据
     */
    public function actionGetProduct() {
        $specJSON = Yii::$app->request->post('specJSON');
        $jsonData = json_decode($specJSON, true);
        if (empty($jsonData)) {
            die(json_encode(array('flag' => 'fail', 'message' => '规格值不符合标准')));
        }
        $goodsId = intval(Yii::$app->request->post('goods_id'));
        //获取货品数据
        $productsObj = new Products();
        $procductsInfo = $productsObj->find()
            ->where('goods_id=:goodsId and spec_array=:specJSON', [':goodsId' => $goodsId, ':specJSON' => $specJSON])
            ->one();
        //匹配到货品数据
        if (empty($procductsInfo)) {
            die(json_encode(array('flag' => 'fail', 'message' => '没有找到相关货品')));
        } else {
            $data = array(
                'id' => $procductsInfo['id'],
                'goods_id' => $procductsInfo['goods_id'],
                'products_no' => $procductsInfo['products_no'],
                'spec_array' => $procductsInfo['spec_array'],
                'store_nums' => $procductsInfo['store_nums'],
                'market_price' => $procductsInfo['market_price'],
                'sell_price' => $procductsInfo['sell_price'],
                'cost_price' => $procductsInfo['cost_price'],
                'weight' => $procductsInfo['weight']
            );
            die(json_encode(array('flag' => 'success', 'info' => $data)));
        }
    }

    /**
     * 添加到收藏
     */
    public function actionFavoriteAdd() {
        $userId = $this->data['shopUserInfo']['userId'];
        $goodsId = Yii::$app->request->get('goods_id');
        if (empty($goodsId)) {
            $errCode = 1;
            $message = '商品id值不能为空';
        } else if (empty($userId)) {
            $errCode = 2;
            $message = '请先登录';
        } else {
            $favoriteModel = new Favorite();
            $favoriteInfo = $favoriteModel->find()
                ->where('user_id=:userId and rid=:goodsId', [':userId' => $userId, ':goodsId' => $goodsId])
                ->one();
            if ($favoriteInfo) {
                $errCode = 3;
                $message = '您已经收藏过此件商品';
            } else {
                $favoriteModel->user_id = $userId;
                $favoriteModel->rid = $goodsId;
                $favoriteModel->time = date('Y-m-d H:i:s');
                $favoriteModel->save();
                //商品收藏信息更新
                $goodsModel = new Goods();
                $goodsInfo = $goodsModel->find()
                    ->select(['id', 'favorite'])
                    ->where('id=:goodsId', [':goodsId' => $goodsId])
                    ->one();
                $goodsInfo->favorite +=1;
                $goodsInfo->update();
                $errCode = 0;
                $message = '收藏成功';
            }
        }
        echo (json_encode(array('errCode' => $errCode, 'errMsg' => $message)));
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout() {
        return $this->render('about');
    }

}
