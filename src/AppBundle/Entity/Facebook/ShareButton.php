<?php
/**
 *
 */

namespace AppBundle\Entity\Facebook;


use AppBundle\Entity\Facebook\Enum\ButtonType;

class ShareButton extends Button
{

    /**
     * @var string $shareContents
     */
    private $shareContents;

    /**
     * ShareButton constructor.
     * @param string $title
     * @param string $shareContents
     */
    public function __construct($title, $shareContents)
    {
        $this->shareContents = $shareContents;
        parent::__construct($title, ButtonType::BUTTON_SHARE);
    }

    /**
     * @return string
     */
    public function getShareContents(): string
    {
        return $this->shareContents;
    }

    /**
     * @param string $shareContents
     */
    public function setShareContents(string $shareContents)
    {
        $this->shareContents = $shareContents;
    }
}