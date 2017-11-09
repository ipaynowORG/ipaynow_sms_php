<?php

/**
 *a
 * @author Jupiter
 *
 * 应用配置类
 */
class Config
{
    static $timezone = "Asia/Shanghai";


    //测试环境
//        static $app_id="1476788813528909";//该处配置您的APPID
//        static $md5_key="c4MlX9GJCi0YI5z3RvpK17wPlscFKpY1";//该处配置您的应用秘钥
//        static $trans_url="https://dby.ipaynow.cn/sms";


//        static $des_key="hSbw2SwTFasdSdddnyS3sijvarq";//该处配置您的DES秘钥 hSbw2SwTFasdSdddnyS3sijvarq
    static $app_id = "150753086263684";//该处配置您的APPID
    static $md5_key = "zHGKLmQaU9PLMEGObyubsV5uhDAeYVqQ";//该处配置您的应用秘钥
    static $des_key = "w75zriHtT85zpCYW3y8Dpw2k";//该处配置您的DES秘钥 hSbw2SwTFasdSdddnyS3sijvarq
    static $trans_url = "https://sms.ipaynow.cn";
    static $query_url = "http://192.168.1.92:10800";

    const VERIFY_HTTPS_CERT = false;
    const CHECK_FUNCODE = "S01"; //行业短信
    const BATCH_FUNCODE = "S02"; //行业短信
    const YX_01_FUNCODE = "YX_01";//营销短信
    const SMS_QUERY_FUNCODE = "SMS_QUERY"; //查询
    const QUERY_FUNCODE = "ID01_Query";
    const CHARSET = "UTF-8";
    const QSTRING_EQUAL = "=";
    const QSTRING_SPLIT = "&";
    const SERVER_PARSE_SUCCESS = "1";
    const SERVER_PARSE_FAIL = "0";
}
