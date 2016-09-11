<?php

namespace backend\utils;

/**
 * 文件处理
 *
 * @author baihua <baihua_2011@163.com>
 */
class File {

    /**
     * 获取文件扩展名
     * @param  String $fileName  文件名
     * @return String 文件后缀名
     */
    public static function getFileSuffix($fileName) {
        $fileInfoArray = pathinfo($fileName);
        return $fileInfoArray['extension'];
    }

    /**
     * 创建文件夹
     * @param String $path  路径
     * @param int    $chmod 文件夹权限
     * @note  $chmod 参数不能是字符串(加引号)，否则linux会出现权限问题
     */
    public static function mkdir($path, $chmod = 0777) {
        return is_dir($path) or ( self::mkdir(dirname($path), $chmod) and mkdir($path, $chmod));
    }

    /**
     * 获取文件类型
     * @param  String $fileName  文件名
     * @return String $filetype  文件类型
     * @note 如果文件不存在，返回false,如果文件后缀名不在识别列表之内，返回NULL，对于docx及elsx格式文档识别在会出现识别为ZIP格式的错误，这是office2007的bug目前尚未修复，请谨慎使用
     */
    public static function getFileType($fileName) {
        $filetype = null;
        if (!is_file($fileName)) {
            return false;
        }

        $fileRes = fopen($fileName, "rb");
        if (!$fileRes) {
            return false;
        }
        $bin = fread($fileRes, 2);
        fclose($fileRes);

        if ($bin != null) {
            $strInfo = unpack("C2chars", $bin);
            $typeCode = intval($strInfo['chars1'] . $strInfo['chars2']);
            $typelist = self::getTypeList();
            foreach ($typelist as $val) {
                if (strtolower($val[0]) == strtolower($typeCode)) {
                    if ($val[0] == 8075) {
                        return array('zip', 'docx', 'xlsx');
                    } else {
                        return $val[1];
                    }
                }
            }
        }
        return $filetype;
    }

    /**
     * 获取文件类型映射关系
     * @return array 文件类型映射关系数组
     */
    public static function getTypeList() {
        return array(
            array('255216', 'jpg'),
            array('13780', 'png'),
            array('7173', 'gif'),
            array('6677', 'bmp'),
            array('6063', 'xml'),
            array('60104', 'html'),
            array('208207', 'xls/doc'),
            array('8075', 'zip'),
            array('8075', 'docx'),
            array('8075', 'xlsx'),
            array("8297", "rar"),
        );
    }

    /**
     * 文件对拷贝
     * @param  String $source   源地址
     * @param  String $dest     目标地址
     * @param  String $oncemore 是否支持子目录拷贝
     * @return bool true:成功; false:失败;
     */
    public static function xcopy($source, $dest, $oncemore = true) {
        if (!file_exists($source)) {
            return "error: $source is not exist!";
        }

        if (is_dir($source)) {
            if (file_exists($dest) && !is_dir($dest)) {
                return "error: $dest is not a dir!";
            }
            if (!file_exists($dest)) {
                self::mkdir($dest, 0777);
            }
            $od = opendir($source);
            while ($one = readdir($od)) {
                if (in_array($one, self::$except)) {
                    continue;
                }
                $result = self::xcopy($source . DIRECTORY_SEPARATOR . $one, $dest . DIRECTORY_SEPARATOR . $one, $oncemore);
                if ($result !== true) {
                    return $result;
                }
            }
            closedir($od);
        } else {
            if (is_dir($dest)) {
                if (func_num_args() > 2 || $oncemore === true) {
                    return "error: $dest is a dir!";
                }
                $result = self::xcopy($source, $dest . DIRECTORY_SEPARATOR . basename($source), $oncemore);
                if ($result !== true) {
                    return $result;
                }
            } else {
                if (!is_dir(dirname($dest))) {
                    self::mkdir(dirname($dest));
                }
                copy($source, $dest);
                touch($dest, filemtime($source));
            }
        }
        return true;
    }

}
