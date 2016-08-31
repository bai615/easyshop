<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Goods;
use common\models\CategoryExtend;
use common\models\GoodsPhotoRelation;
use common\models\Products;
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
        return $this->render('index');
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
            dprint($goodsInfo['brand_id']);
//            $brandModel = new IModel('brand');
//            $brandInfo = $brandModel->find(array(
//                'select' => 'name',
//                'condition' => 'id=:brindId',
//                'params' => array('brandId' => $goodsInfo['brand_id'])
//            ));
//            if ($brandInfo) {
//                $goodsInfo['brand_name'] = $brandInfo['name'];
//            }
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

        return $this->render('products', ['goodsInfo' => $goodsInfo]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
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
