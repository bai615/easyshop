<?php

namespace frontend\payments;

use Yii;
use common\models\Payment;
use common\utils\CommonTools;

/**
 * 易极付支付
 *
 * @author baihua <baihua_2011@163.com>
 */
class YijiPay extends BasePay {
    
    public $type = 'YijiPay';

    /**
     * 同步支付回调
     * @param type $ExternalData
     * @param type $paymentId
     * @param type $money
     * @param type $message
     * @param type $orderNo
     */
    public function callback($ExternalData, &$paymentId, &$money, &$message, &$orderNo) {
        $partnerKey = Payment::getConfigParam($paymentId, 'M_PartnerKey');
        $userId = Yii::$app->controller->data['shopUserInfo']['userId'];
        file_put_contents("./pay.txt", 'callback', FILE_APPEND);
        file_put_contents("./pay.txt", print_r($ExternalData, true), FILE_APPEND);
        if (!$ExternalData['orderNo'] || !$ExternalData['sign']) {
            $message = '缺少必要参数';
            return false;
        }
        $orderNo = substr($ExternalData['orderNo'], 0, 20);
        $oldSign = $ExternalData['sign'];
        $newSign = CommonTools::getSign($ExternalData, $partnerKey);
        if ($oldSign == $newSign) {
            //支付单号
            switch ($ExternalData['resultCode']) {
                case 'EXECUTE_SUCCESS': {
                        $this->recordTradeNo($orderNo, $ExternalData['tradeNo']);
                        return true;
                    }
                    break;

                    default : {
                        $message = '支付失败，请到用户中心重新支付';
                        return false;
                    }
                    break;
            }
        } else {
            $message = '校验码不正确';
        }
        return false;
    }

    /**
     * 获取要发送的数据数组结构
     * @param type $paymentInfo
     * @return type
     */
    public function getSendData($paymentInfo) {
        $return = array();
        //基本参数
        $return['protocol'] = 'httpPost'; //协议，否
        $return['service'] = 'commonTradePay'; //服务代码，是
        $return['version'] = '2.0'; //服务版本，否
        $return['partnerId'] = $paymentInfo['M_PartnerId']; //商户id，是
        $return['orderNo'] = $paymentInfo['M_OrderNO'] . \common\utils\CommonTools::getRandChar("4"); //请求订单号，是
        $return['signType'] = 'MD5'; //签名方式，否
        $return['returnUrl'] = $this->callbackUrl; //页面跳转返回URL，否
        $return['notifyUrl'] = $this->serverCallbackUrl; //异步通知url，否
        //业务参数
        $return['outOrderNo'] = $paymentInfo['M_OrderNO'];
        $return['sellerUserId'] = $paymentInfo['M_PartnerId'];
        $goodsInfo = explode('@@@', $paymentInfo['products']);
        $goodsClauses = array();
        if ($goodsInfo) {
            foreach ($goodsInfo as $name) {
                $goodsClauses[] = array('name' => $name);
            }
        }
        $return['goodsClauses'] = str_replace("\"", "'", json_encode($goodsClauses));
        $return['tradeAmount'] = $paymentInfo['M_Amount'];

        //生成签名结果
        $mysign = \common\utils\CommonTools::getSign($return, $paymentInfo['M_PartnerKey']);
        //签名结果与签名方式加入请求提交参数组中
        $return['sign'] = $mysign;
//        dprint($return);
        return $return;
    }

    /**
     * 获取提交地址
     * @return type
     */
    public function getSubmitUrl() {
        //易极付开发平台
        //https://apidoc.yiji.com/website/index.html
        //易极付官网
        //https://www.yiji.com/index.htm
        return 'http://openapi.yijifu.net/gateway.html'; //测试环境
    }

    /**
     * 异步支付回调
     * @param type $ExternalData
     * @param type $paymentId
     * @param type $money
     * @param type $message
     * @param type $orderNo
     */
    public function serverCallback($ExternalData, &$paymentId, &$money, &$message, &$orderNo) {
        
    }

//put your code here
}
