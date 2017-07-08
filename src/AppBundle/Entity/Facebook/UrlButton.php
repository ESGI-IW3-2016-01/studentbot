<?php
/**
 *
 */

namespace AppBundle\Entity\Facebook;


use AppBundle\Entity\Facebook\Enum\ButtonType;

class UrlButton extends Button
{
    /**
     * @var string $url
     */
    private $url;

    /**
     * @var string $height
     */
    private $height;

    public function __construct(string $title, string $url, string $height = 'full')
    {
        $this->height = $height;
        parent::__construct($title, ButtonType::BUTTON_URL);
    }
}