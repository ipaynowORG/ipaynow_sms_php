<?php
/**
 * Created by PhpStorm.
 * User: ipaynow0929
 * Date: 2017/11/8
 * Time: 14:07
 */

require_once '../conf/Config.php';
require_once '../services/SendService.php';
require_once '../utils/Log.php';
require_once '../services/BatchMessageTemplet.php';

$batch1 = new BatchMessageTemplet("content1","1","17701087752");
$batch2 = new BatchMessageTemplet("content2","2","17718489565");
$arr = array($batch1,$batch2);

//营销
//print SendService::salesMessage("sales2","17701087752","营销短信测试","www.baidu.com");
//行业短信
//print SendService::industryMessage("industry2","17701087752","php 行业短信测试","www.baidu.com");

//行业短信
//print SendService::batchMessage("batch0","17701087752",json_encode($arr),"www.baidu.com");

print SendService::queryMessage("400001201711091114172180249","17701087752");