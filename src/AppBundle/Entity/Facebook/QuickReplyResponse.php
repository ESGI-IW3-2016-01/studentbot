<?php
/**
 *
 */

namespace AppBundle\Entity\Facebook;


use Doctrine\Common\Collections\ArrayCollection;

class QuickReplyResponse
{

    private $recipient;
    private $text;
    private $quickReplies;

    /**
     * QuickReplyResponse constructor.
     */
    public function __construct()
    {
        $this->quickReplies = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getQuickReplies(): ArrayCollection
    {
        return $this->quickReplies;
    }

    /**
     * @param ArrayCollection $quickReplies
     */
    public function setQuickReplies(ArrayCollection $quickReplies)
    {
        $this->quickReplies = $quickReplies;
    }

    /**
     * @param QuickReply $quickReply
     */
    public function addQuickReply(QuickReply $quickReply)
    {
        $this->quickReplies->add($quickReply);
    }

    /**
     * @param QuickReply $quickReply
     */
    public function removeQuickReply(QuickReply $quickReply)
    {
        $this->quickReplies->removeElement($quickReply);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (count($this->quickReplies) > 0) {

            $replies = [];

            /** @var QuickReply $quickReply */
            foreach ($this->quickReplies as $quickReply) {
                $replies[] = $quickReply->toArray();
            }

            $array = [
                'recipient' => [
                    'id' => $this->recipient
                ],
                'message' => [
                    'text' => $this->text,
                    'quick_replies' => $replies
                ]
            ];
        } else {
            $array = [];
        }
        return $array;
    }
}
