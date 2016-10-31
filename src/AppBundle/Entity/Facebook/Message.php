<?php

/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 26/10/2016
 * Time: 22:38
 */
class Message
{
    private $id;

    private $mid;
    private $seq;
    private $text;

    private $sender;
    private $receiver;
    private $date;

    private $attachment;

    // Custom Data
    private $quickReply;

    /**
     * Message constructor.
     * @param $id
     * @param $sender
     * @param $receiver
     * @param null $text
     * @param null $date
     * @param null $mid
     * @param int $seq
     * @param Attachment|null $attachment
     * @param null $quickReply
     */
    public function __construct(
        $id,
        $sender,
        $receiver,
        $text = null,
        $date = null,
        $mid = null,
        $seq = 0,
        Attachment $attachment = null,
        $quickReply = null)
    {
        $this->id = $id;
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->$date = new DateTime($date);
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


    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return mixed
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param mixed $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
}