<?php

require_once '../conf/Config.php';
/**
 * Created by PhpStorm.
 * User: John
 * Date: 2015/9/24
 * Time: 14:26
 */
class DESUtil
{
    public static function encrypt($desKey,$data){
        $cipher=MCRYPT_3DES;
        $mode=MCRYPT_MODE_ECB;
        if (strlen($desKey) > 24){
            $desKey = substr($desKey,0,24);
        }
        return mcrypt_encrypt($cipher,$desKey,$data,$mode);
    }

    public static function decrypt($desKey,$data){
        $cipher=MCRYPT_3DES;
        $mode=MCRYPT_MODE_ECB;
        if (strlen($desKey) > 24){
            $desKey = substr($desKey,0,24);
        }
        return mcrypt_decrypt($cipher,$desKey,$data,$mode);
    }
}