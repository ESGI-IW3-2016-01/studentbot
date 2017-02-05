<?php

namespace AppBundle\Entity\Facebook;

use DateTime;
use AppBundle\Entity\Facebook\Enum\SenderAction;
use AppBundle\Entity\Facebook\Attachment;


class SendMessage
{
    private $text;
    private $recipient;
    /** @var SenderAction senderAction */
    private $senderAction;
    /** @var Attachment */
    private $attachment;

    /**
     * SendMessage constructor.
     * @param $text
     * @param $recipient
     * @param SenderAction $senderAction
     * @param Attachment $attachment
     */
    public function __construct($recipient, $text = null, SenderAction $senderAction = null, Attachment $attachment = null)
    {
        $this->recipient = $recipient;
        $this->text = $text;
        $this->senderAction = $senderAction;
        $this->attachment = $attachment;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return SenderAction
     */
    public function getSenderAction()
    {
        return $this->senderAction;
    }

    /**
     * @param SenderAction $senderAction
     */
    public function setSenderAction($senderAction)
    {
        $this->senderAction = $senderAction;
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
}