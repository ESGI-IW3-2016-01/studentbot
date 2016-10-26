<?php

/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 26/10/2016
 * Time: 22:34
 */
class Received
{

    private $sender;
    private $recipient;
    private $sendAt;
    private $message;

    /**
     * Received constructor.
     * @param $sender
     * @param $recipient
     * @param $sendAt
     * @param $message
     */
    public function __construct($sender, $recipient, $sendAt, $message)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->sendAt = new DateTime($sendAt);
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return DateTime
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }

    /**
     * @param DateTime $sendAt
     */
    public function setSendAt($sendAt)
    {
        $this->sendAt = $sendAt;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Message $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    
}