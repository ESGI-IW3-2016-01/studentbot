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
    private $attachment;

    /**
     * SendMessage constructor.
     * @param string $text
     * @param string $recipient
     * @param SenderAction $senderAction
     * @param string $attachment
     */
    public function __construct($recipient, $text = null, SenderAction $senderAction = null, $attachment = null)
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
     * @return string
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * @param string $attachment
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
    }
}