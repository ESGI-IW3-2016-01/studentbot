<?php

/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 26/10/2016
 * Time: 22:38
 */
class Message
{

    private $mid;
    private $seq;
    private $text;

    private $attachment;

    // Custom Data
    private $quickReply;

    /**
     * Message constructor.
     * @param $mid
     * @param $seq
     * @param $text
     * @param Attachment $attachment
     * @param $quickReply
     */
    public function __construct($mid, $seq, $text, Attachment $attachment = null, $quickReply = null)
    {
        $this->mid = $mid;
        $this->seq = $seq;
        $this->text = $text;
        $this->attachment = $attachment;
        $this->quickReply = $quickReply;
    }


    /**
     * @return string
     */
    public function getMid()
    {
        return $this->mid;
    }

    /**
     * @param string $mid
     */
    public function setMid($mid)
    {
        $this->mid = $mid;
    }

    /**
     * @return integer
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * @param integer $seq
     */
    public function setSeq($seq)
    {
        $this->seq = $seq;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return Attachment
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * @param Attachment $attachment
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * @return mixed
     */
    public function getQuickReply()
    {
        return $this->quickReply;
    }

    /**
     * @param mixed $quickReply
     */
    public function setQuickReply($quickReply)
    {
        $this->quickReply = $quickReply;
    }



}