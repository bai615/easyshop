<?php

namespace backend\utils;

/**
 * 图片处理类
 *
 * @author baihua <baihua_2011@163.com>
 */
class Image {

    /**
     * 生成缩略图
     * @param string  $fileName 原图路径
     * @param int     $width    缩略图的宽度
     * @param int     $height   缩略图的高度
     * @param string  $extName  缩略图文件名附加值
     * @param string  $saveDir  缩略图存储目录
     * @return string 缩略图文件名
     */
    public static function thumb($fileName, $width = 200, $height = 200, $extName = '_thumb', $saveDir = '') {
        $GD = new GD($fileName);

        if ($GD) {
            $GD->resize($width, $height);
            $GD->pad($width, $height);

            //存储缩略图
            if ($saveDir && File::mkdir($saveDir)) {
                //生成缩略图文件名
                $thumbBaseName = $extName . basename($fileName);
                $thumbFileName = $saveDir . basename($thumbBaseName);

                $GD->save($thumbFileName);
                return $thumbFileName;
            }
            //直接输出浏览器
            else {
                return $GD->show();
            }
        }
        return null;
    }

}
