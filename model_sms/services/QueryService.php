<?php
/**
 * Created by PhpStorm.
 * User: ipaynow0929
 * Date: 2017/11/8
 * Time: 16:24
 */
class QueryService{

    public static function query($mhtOrderNo,$mobile,$content,$notifyUrl){
        $req=array();
        $req["funcode"]=config::SMS_QUERY_FUNCODE;
        $req["appId"]=config::$app_id;
        $req["mhtOrderNo"]= $mhtOrderNo;//date("YmdHis");
        $req["mobile"]=$mobile;
        $req["content"]=$content;
        $req["notifyUrl"]=$notifyUrl;
        return self::buildAndSendCheckReq($req);
    }
}