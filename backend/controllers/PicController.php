<?php

namespace backend\controllers;

use Yii;
use backend\utils\Upload;
use common\models\SpecPhoto;

/**
 * 图库处理
 *
 * @author baihua <baihua_2011@163.com>
 */
class PicController extends BaseController {

    /**
     * 规格图片上传
     */
    public function actionUploadFile() {
        //上传状态
        $state = false;

        //规格索引值
        $specIndex = Yii::$app->request->get('specIndex');
        if ($specIndex === null) {
            $message = '没有找到规格索引值';
        } else {
            //本地上传方式
            if (isset($_FILES['attach']) && $_FILES['attach']['name'][0] != '') {
                $photoInfo = $this->upload();
                if ($photoInfo['flag'] == 1) {
                    $fileName = $photoInfo['dir'] . $photoInfo['name'];
                    $state = true;
                }
            }

            //远程网络方式
            else if ($fileName = Yii::$app->request->post('outerSrc')) {
                $state = true;
            }

            //图库选择方式
            else if ($fileName = Yii::$app->request->post('selectPhoto')) {
                $state = true;
            }
        }

        //根据状态值进行
        if ($state == true) {
            die("<script type='text/javascript'>parent.art.dialog({id:'addSpecWin'}).iframe.contentWindow.updatePic(" . $specIndex . ",'" . Yii::$app->params['upload_url'] . $fileName . "');</script>");
        } else {
            die("<script type='text/javascript'>alert('添加图片失败');window.history.go(-1);</script>");
        }
    }

    //本地上传方式
    function upload() {
        //图片上传
        $upObj = new Upload(Yii::$app->params['upload_path']);

        //目录散列
        $dir = 'upload' . '/' . date('Y') . "/" . date('m') . "/" . date('d');
        $upObj->setDir($dir);
        $upState = $upObj->execute();

        //检查上传状态
        foreach ($upState['attach'] as $val) {
            if ($val['flag'] == 1) {
                //实例化
                $obj = new SpecPhoto();
                $obj->address = $val['dir'] . $val['name'];
                $obj->name = $val['ininame'];
                $obj->create_time = date('Y-m-d H:i:s', time());
                $obj->save();
            }
        }
        if (count($upState['attach']) == 1) {
            return $upState['attach'][0];
        } else {
            return $upState['attach'];
        }
    }

}
