<?php

require_once '../conf/Config.php';
require_once '../utils/DESUtil.php';
require_once '../utils/Tools.php';
require_once '../utils/NetUtil.php';
require_once '../utils/Log.php';

class SendService
{

    /**
     * 行业短信
     * @param $mhtOrderNo  商家的订单号,每次请求请保持唯一
     * @param $mobile 手机号
     * @param $content 短息内容
     * @param $notifyUrl 后台通知地址
     * @param $isTest 是否测试 true测试，false生产
     * @return bool|string
     */
    public static function industryMessage($mhtOrderNo, $mobile, $content, $notifyUrl,$isTest=true)
    {
        $req = array();
        $req["funcode"] = config::CHECK_FUNCODE;
        $req["appId"] = config::$app_id;
        $req["mhtOrderNo"] = $mhtOrderNo;//date("YmdHis");
        $req["mobile"] = $mobile;
        $req["content"] = $content;
        $req["notifyUrl"] = $notifyUrl;
        return self::buildAndSendCheckReq($req,$isTest);
    }

    /**
     * 营销短信
     * @param $mhtOrderNo  商家的订单号,每次请求请保持唯一
     * @param $mobile 手机号
     * @param $content 短息内容
     * @param $notifyUrl 后台通知地址
     * @param $isTest 是否测试 true测试，false生产
     * @return bool|string
     */
    public static function salesMessage($mhtOrderNo, $mobile, $content, $notifyUrl,$isTest)
    {
        $req = array();
        $req["funcode"] = config::YX_01_FUNCODE;
        $req["appId"] = config::$app_id;
        $req["mhtOrderNo"] = $mhtOrderNo;//date("YmdHis");
        $req["mobile"] = $mobile;
        $req["content"] = $content;
        $req["notifyUrl"] = $notifyUrl;
        return self::buildAndSendCheckReq($req,$isTest);
    }

    /**
     * @param $contents
     * @param $notifyUrl
     * @return bool|string
     */
    public static function batchMessage($contents, $notifyUrl,$isTest)
    {
        $req = array();
        $req["funcode"] = config::BATCH_FUNCODE;
        $req["appId"] = config::$app_id;
        $req["contents"] = $contents;
        $req["notifyUrl"] = $notifyUrl;
        return self::buildAndSendCheckReq($req,$isTest);
    }

    /**
     * 订单查询
     * @param $nowPayOrderNo 现在支付订单号
     * @param $mobile 手机号
     * @param $isTest 是否测试 true测试，false生产
     * @return bool|string  tradeStatus(A00H 处理中, A001-推送成功，A002-推送失败)
     */
    public static function queryMessage($nowPayOrderNo, $mobile,$isTest=true)
    {
        $req = array();
        $req["funcode"] = config::SMS_QUERY_FUNCODE;
        $req["appId"] = config::$app_id;
        $req["nowPayOrderNo"] = $nowPayOrderNo;
        $req["mobile"] = $mobile;
        /**
         * appId=150753086263684&funcode=SMS_QUERY&mhtOrderNo=sales2&responseCode=00&responseMsg=success&responseTime=20171109114903&tradeStatus=A001&transTime=2017-11-09 11:14:17
         */
        return self::buildAndSendQueryReq($req,$isTest);
    }


    public static function buildAndSendCheckReq(Array $req,$isTest)
    {
        $req_content = self::buildReqTemplate($req["funcode"], $req);
        echo "请求报文：" . $req_content;
        echo "\n";
        if ($isTest){
            $url = Config::$test_url;
        }else $url = Config::$pro_url;
        $resp_content = NetUtil::sendMessage($req_content, $url);
        echo "\n";
        echo "应答报文：" . $resp_content;
        return Self::parseResp($resp_content);
    }

    public static function buildAndSendQueryReq(Array $req,$isTest)
    {
        $req_content = Tools::createLinkString($req, true, false);
        $req_content = $req_content . "&mchSign=" . md5($req_content . '&' . Config::$md5_key);
        if ($isTest){
            $url = Config::$test_url;
        }else $url = Config::$pro_url;
        $resp_content = NetUtil::sendMessage($req_content, $url);
        print "\nresp : " . $resp_content;
        //Log::outLog("查询接口应答:",$resp_content);
        return Self::parseResp($resp_content);
    }

    private static function buildReqTemplate($funcode, Array $req_content)
    {
        $original_text = Tools::createLinkString($req_content, true, false);
        $header = "funcode=" . $funcode;
        $message_data_one = base64_encode('appId=' . Config::$app_id);

        echo "第一部分：" . $message_data_one;
        echo "\n";
        $message_data_two = base64_encode(DESUtil::encrypt(Config::$des_key, $original_text));
        echo "第二部分：" . $message_data_two;
        echo "\n";
        echo "contetn :" .$original_text . '&' . Config::$md5_key;
        echo "md5 : ".md5($original_text . '&' . Config::$md5_key);
        $message_data_three = base64_encode(md5($original_text . '&' . Config::$md5_key));
        echo "\n第三部分：" . $message_data_three;
        echo "\n";
        $message = urlencode($message_data_one . '|' . $message_data_two . '|' . $message_data_three);

        return $header . '&message=' . $message;
    }

    private static function parseResp($resp_content)
    {
        $resp = explode("|", $resp_content);
        if ($resp[0] == Config::SERVER_PARSE_SUCCESS) {
            //现在支付服务器解析成功后的处理
            return Self::parseSuccessResp($resp);
        } else {
            //现在支付服务器解析失败后的处理
            return Self::parseErrorResp($resp);
        }
    }

    private static function parseSuccessResp(Array $resp)
    {
        $original_text = trim(DESUtil::decrypt(Config::$des_key, base64_decode($resp[1])));
        $server_sign = base64_decode($resp[2]);
        if (Self::isCheckSignatureSucess($original_text, $server_sign)) {
            //签名验证成功处理
            return $original_text;
        } else {
            //签名验证失败处理
            return "CHECK_SIGN_FAILS";
        }
    }

    private static function parseErrorResp(Array $resp)
    {
        return base64_decode($resp[1]);
    }

    /**
     * 判断是否验证签名成功
     * @param $original_text
     * @param $server_sign
     * @return bool
     */
    private static function isCheckSignatureSucess($original_text, $server_sign)
    {
        $local_sign = md5($original_text . '&' . Config::$md5_key);
        if ($local_sign == $server_sign) {
            //验证签名成功
            return true;
        } else {
            //验证签名失败
            return false;
        }
    }
}