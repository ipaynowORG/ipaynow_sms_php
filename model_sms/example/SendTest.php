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

print json_encode($arr);

//营销
print SendService::salesMessage(date("YmdHis"),"17701087752","php 测试 尊敬的用户，您已获得哇哈哈套装一套，请于9月16日-17日，到店领取。地址：呼和浩特工农兵路盛泰华宴底商，电话：0471-6667070。当日购车可享受0首付、免服务费等超值优惠，另可获得价值1万元的汽车养护好礼，更有现场返利50%！报名猛戳：http://t.cn/Rp9GJJs 回复TD退订","www.baidu.com");
//行业短信
//print SendService::industryMessage("industry2","17701087752","php 行业短信测试","www.baidu.com");

//行业短信
//print SendService::batchMessage("batch0","17701087752",json_encode($arr),"www.baidu.com");