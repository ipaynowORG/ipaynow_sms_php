<?php
/**
 * Created by PhpStorm.
 * User: ipaynow0929
 * Date: 2017/11/8
 * Time: 15:37
 */

class BatchMessageTemplet
{
    var $content;
    var $mchOrderNo;
    var $phone;

    function __construct($content, $mchOrderNo,$phone){
        $this->content = $content;
        $this->mchOrderNo = $mchOrderNo;
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getMchOrderNo()
    {
        return $this->mchOrderNo;
    }

    /**
     * @param mixed $mchOrderNo
     */
    public function setMchOrderNo($mchOrderNo)
    {
        $this->mchOrderNo = $mchOrderNo;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }



}