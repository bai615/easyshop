<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Goods;
use common\models\CategoryExtend;
use common\models\GoodsPhotoRelation;
use common\models\Products;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
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

        //商品图片
        $goodsPhotoList = GoodsPhotoRelation::getGoodsPhotoList($goodsId);

        if ($goodsPhotoList) {
            $goodsPhotoArr = array();
            foreach ($goodsPhotoList as $key => $value) {
                $goodsPhotoArr[$key]['img'] = $value['img'];
                $goodsPhotoArr[$key]['photo_id'] = $value['photo_id'];
                //对默认第一张图片位置进行前置
                if ($value['img'] == $goodsInfo['img']) {
                    $temp = $goodsPhotoArr[0];
                    $goodsPhotoArr[0]['img'] = $value['img'];
                    $goodsPhotoArr[0]['photo_id'] = $value['photo_id'];
                    $goodsPhotoArr[$key] = $temp;
                }
            }
            $goodsInfo['photo'] = $goodsPhotoArr;
        }

        //获得商品的价格区间
        $productModel = new Products();
        $productList = $productModel->find()
            ->select(['max(sell_price) as maxSellPrice', 'min(sell_price) as minSellPrice', 'max(market_price) as maxMarketPrice', 'min(market_price) as minMarketPrice'])
            ->where('goods_id=:goodsId', [':goodsId' => $goodsId])
            ->one();
        if ($productList) {
            $priceArea['maxSellPrice'] = $productList['maxSellPrice'];
            $priceArea['minSellPrice'] = $productList['minSellPrice'];
            $priceArea['minMarketPrice'] = $productList['minMarketPrice'];
            $priceArea['maxMarketPrice'] = $productList['maxMarketPrice'];
            $goodsInfo['price_area'] = $priceArea;
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

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                'model' => $model,
        ]);
    }

}
