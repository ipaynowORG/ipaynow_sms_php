<?php

require_once '../conf/Config.php';
require_once '../services/SendService.php';
require_once '../utils/Log.php';
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2015/9/24
 * Time: 13:56
 */
class Check
{
    public function toCheck(){
        $req=array();
        $req["funcode"]=config::CHECK_FUNCODE;
		$req["appId"]=config::$app_id;
        $req["mhtOrderNo"]=date("YmdHis");
		$req["mobile"]=$_GET["mobile"];
		$req["content"]=$_GET["content"];
		$req["notifyUrl"]="http://www.baidu.com";
        $req_content=Services::buildAndSendCheckReq($req); 		
        Log::outLog("验证信息接口应答:",$req_content);
        echo $req_content;
		
		
    }
}
$api=new Check();
$api->toCheck();