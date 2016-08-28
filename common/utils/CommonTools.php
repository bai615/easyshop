<?php

namespace common\utils;

/**
 * 公共方法集合
 *
 * @author baihua <baihua_2011@163.com>
 */
class CommonTools {

    /**
     * @brief 获取评价分数
     * @param $grade float 分数
     * @param $comments int 评论次数
     * @return float
     */
    public static function gradeWidth($grade, $comments = 1) {
        return $comments == 0 ? 0 : 14 * ($grade / $comments);
    }

    /**
     * 获取加密后的密码
     * @param type $password
     * @param type $salt
     * @param type $prefix
     * @return type
     */
    public static function getPwd($password, $salt = '', $prefix = 'yiishop_') {
        return md5(sha1($prefix . trim($password) . $salt));
    }

    /**
     * 前台用户自动Cookie名称
     * @return type
     */
    public static function getAutoCookieName() {
        return md5(Yii::app()->params['auto_login_cookie_name']);
    }

    /**
     * 获得用户的真实IP地址
     * @access  public
     * @return  string
     */
    public static function real_ip() {
        static $realip = NULL;

        if ($realip !== NULL) {
            return $realip;
        }

        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr AS $ip) {
                    $ip = trim($ip);

                    if ($ip != 'unknown') {
                        $realip = $ip;

                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }

        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

        return $realip;
    }

    /*     * *******************************************************************
      函数名称:encrypt
      函数作用:加密解密字符串
      使用方法:
      加密     :encrypt('str','E','nowamagic');
      解密     :encrypt('被加密过的字符串','D','nowamagic');
      参数说明:
      $string   :需要加密解密的字符串
      $operation:判断是加密还是解密:E:加密   D:解密
      $key      :加密的钥匙(密匙);
     * ******************************************************************* */

    public static function encrypt($string, $operation, $key = '') {
        $key = md5($key);
        $key_length = strlen($key);
        $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result.=chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'D') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return'';
            }
        } else {
            return str_replace('=', '', base64_encode($result));
        }
    }

    //[公共方法]通过解析products表中的spec_array转化为格式：key:规格名称;value:规格值
    public static function show_spec($specJson) {
        $specArray = CJSON::decode($specJson);
        $spec = array();

        if ($specArray) {
            foreach ($specArray as $val) {
                if ($val['type'] == 1) {
                    $spec[$val['name']] = $val['value'];
                } else {
                    $spec[$val['name']] = '<img src="' . $val['value'] . '" class="img_border" style="width:15px;height:15px;" />';
                }
            }
        }
        return $spec;
    }

    /**
     * 获取签名字符串，签名验证算法
     * @param type $data
     * @param type $skey
     * @return type
     */
    public static function getSign($data = array(), $skey = '') {
        ksort($data);
        unset($data['sign']);
        $strSign = '';
        foreach ($data as $key => $value) {
            $strSign.=$key . "=" . ($value) . "&";
        }
        $strSign = substr($strSign, 0, -1);
//        $dataStr = http_build_query($data);
        return md5($strSign . $skey);
    }
    
    /**
     * 显示警告信息
     * @param type $message
     */
    public static function showWarning($message = '') {
		$data['message'] = $message;
		$result = Yii::app()->getController()->render('/common/warning', $data, 1);
		die($result);
	}

}
