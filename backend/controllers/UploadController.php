<?php

namespace backend\controllers;

use Yii;
use backend\utils\PhotoUpload;

/**
 * 上传文件或者图片
 *
 * @author baihua <baihua_2011@163.com>
 */
class UploadController extends BaseController {

    public $enableCsrfValidation = false;

    /**
     * 商品添加中图片上传的方法
     */
    public function actionGoodsImgUpload() {

//        $this->controller->enableCsrfValidation = false;
        //调用文件上传类
        $photoObj = new PhotoUpload(Yii::$app->params['upload_path']);
        $photo = current($photoObj->run());
        //判断上传是否成功，如果float=1则成功
        if ($photo['flag'] == 1) {
            $result = array(
                'flag' => 1,
                'img' => $photo['img']
            );
        } else {
            $result = array('flag' => $photo['flag']);
        }
        echo json_encode($result);
    }

}
