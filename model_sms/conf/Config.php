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


    static $app_id = "xxxxxxxxxxxx";//该处配置您的APPID
    static $md5_key = "xxxxxxxxxxxxxx";//该处配置您的应用秘钥
    static $des_key = "xxxxxxxxxxxxxxxxxx";//该处配置您的DES秘钥
    static $pro_url = "https://sms.ipaynow.cn";//生产url
    static $test_url = "https://dby.ipaynow.cn/sms";//测试url

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
