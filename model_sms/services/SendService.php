<?php

require_once '../conf/Config.php';
require_once '../utils/DESUtil.php';
require_once '../utils/Tools.php';
require_once '../utils/NetUtil.php';
require_once '../utils/Log.php';

class SendService
{

    public static function industryMessage($mhtOrderNo,$mobile,$content,$notifyUrl){
        $req=array();
        $req["funcode"]=config::CHECK_FUNCODE;
        $req["appId"]=config::$app_id;
        $req["mhtOrderNo"]= $mhtOrderNo;//date("YmdHis");
        $req["mobile"]=$mobile;
        $req["content"]=$content;
        $req["notifyUrl"]=$notifyUrl;
        return self::buildAndSendCheckReq($req);
    }

    public static function salesMessage($mhtOrderNo,$mobile,$content,$notifyUrl){
        $req=array();
        $req["funcode"]=config::YX_01_FUNCODE;
        $req["appId"]=config::$app_id;
        $req["mhtOrderNo"]= $mhtOrderNo;//date("YmdHis");
        $req["mobile"]=$mobile;
        $req["content"]=$content;
        $req["notifyUrl"]=$notifyUrl;
        return self::buildAndSendCheckReq($req);
    }

    public static function batchMessage($mhtOrderNo,$contents,$notifyUrl){
        $req=array();
        $req["funcode"]=config::BATCH_FUNCODE;
        $req["appId"]=config::$app_id;
        $req["mhtOrderNo"]= $mhtOrderNo;//date("YmdHis");
        $req["contents"]=$contents;
        $req["notifyUrl"]=$notifyUrl;
        return self::buildAndSendCheckReq($req);
    }

    public static function buildAndSendCheckReq(Array $req)

    {
        $req_content = self::buildReqTemplate($req["funcode"], $req);
        echo "请求报文：" . $req_content;
        echo "\n";
        $resp_content = NetUtil::sendMessage($req_content, Config::$trans_url);
        echo "\n";
        echo "应答报文：" . $resp_content;
        return Self::parseResp($resp_content);
    }

    public static function buildAndSendQueryReq(Array $req)
    {
        $req_content = self::buildReqTemplate(Config::QUERY_FUNCODE, $req);
        //Log::outLog("查询接口请求:",$req_content);
        $resp_content = NetUtil::sendMessage($req_content, Config::$query_url);
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
        $message_data_two = base64_encode(DESUtil::encrypt($original_text));
        echo "第二部分明文：" . $original_text;
        echo "\n";
        $message_data_three = base64_encode(md5($original_text . '&' . Config::$md5_key));
        echo "第三部分：" . $message_data_three;
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
        $original_text = trim(DESUtil::decrypt(base64_decode($resp[1])));
        $server_sign = base64_decode($resp[2]);
        if (Self::isCheckSignatureSucess($original_text, $server_sign)) {
            //签名验证成功处理
            Log::outLog("验证签名", "验证成功");
            return $original_text;
        } else {
            //签名验证失败处理
            Log::outLog("验证签名", "验证失败");
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
        Log::outLog("本地签名:", $local_sign);
        Log::outLog("服务器签名:", $server_sign);
        if ($local_sign == $server_sign) {

            //验证签名成功
            return true;
        } else {
            //验证签名失败
            return false;
        }
    }
}