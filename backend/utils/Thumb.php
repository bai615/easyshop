<?php

namespace backend\utils;

/**
 * 动态生成缩略图类
 *
 * @author baihua <baihua_2011@163.com>
 */
class Thumb {

    //缩略图路径
    private $thumbDir = "_thumb/";
    //图片物理路径
    private $dir;

    /**
     * 构造函数
     * @param type $dir
     */
    public function __construct($dir = '') {
        $this->setDir($dir);
        $this->setThumbDir($dir . '_thumb/');
    }

    /**
     * 设置图片路径
     * @param string $dir
     */
    public function setDir($dir) {
        $this->dir = $dir;
    }

    /**
     * 设置缩略图路径
     * @param string $thumbDir
     */
    public function setThumbDir($thumbDir) {
        $this->thumbDir = $thumbDir;
    }

    /**
     * 获取缩略图物理路径
     */
    public static function getThumbDir() {
        return $this->thumbDir;
    }

    /**
     * 生成缩略图
     * @param string $imgSrc 图片路径
     * @param int $width 图片宽度
     * @param int $height 图片高度
     * @return string WEB图片路径名称
     */
    public static function get($imgSrc, $width = 100, $height = 100) {
        if ($imgSrc == '') {
            return '';
        }

        //远程图片
        if (strpos($imgSrc, "http") === 0) {
            $sourcePath = $imgSrc;
            $urlArray = parse_url($imgSrc);
            if (!isset($urlArray['path'])) {
                return '';
            }
            $dirname = dirname($urlArray['path']);
        }
        //本地图片
        else {
            $sourcePath = $this->dir . $imgSrc;
            if (is_file($sourcePath) == false) {
                return '';
            }
            $dirname = dirname($imgSrc);
        }

        //缩略图文件名
        $preThumb = "{$width}_{$height}_";
        $thumbFileName = $preThumb . basename($imgSrc);

        //缩略图目录
        $thumbDir = $this->getThumbDir() . trim($dirname, "/") . "/";
        $webThumbDir = $this->thumbDir . trim($dirname, "/") . "/";
        if (is_file($thumbDir . $thumbFileName) == false) {
            Image::thumb($sourcePath, $width, $height, $preThumb, $thumbDir);
        }
        return $webThumbDir . $thumbFileName;
    }

}
