<?php

namespace backend\utils;

/**
 * Description of Filter
 *
 * @author baihua <baihua_2011@163.com>
 */
class Filter {

    /**
     * 检测文件是否有可执行的代码
     * @param string  $file 要检查的文件路径
     * @return boolean 检测结果
     */
    public static function checkHex($file) {
        $resource = fopen($file, 'rb');
        $fileSize = filesize($file);
        fseek($resource, 0);
        // 读取文件的头部和尾部
        if ($fileSize > 512) {
            $hexCode = bin2hex(fread($resource, 512));
            fseek($resource, $fileSize - 512);
            $hexCode .= bin2hex(fread($resource, 512));
        }
        // 读取文件的全部内容
        else {
            $hexCode = bin2hex(fread($resource, $fileSize));
        }
        fclose($resource);
        /* 匹配16进制中的 <% (  ) %> */
        /* 匹配16进制中的 <? (  ) ?> */
        /* 匹配16进制中的 <script  /script>  */
        if (preg_match("/(3c25.*?28.*?29.*?253e)|(3c3f.*?28.*?29.*?3f3e)|(3C534352495054.*?2F5343524950543E)|(3C736372697074.*?2F7363726970743E)/is", $hexCode)) {
            return false;
        } else {
            return true;
        }
    }

}
