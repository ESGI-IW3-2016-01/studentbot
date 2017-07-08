<?php
/**
 *
 */

namespace AppBundle\Entity\Facebook;


class Button
{
    /**
     * @var string $type
     */
    private $type;

    /**
     * @var string $title
     */
    private $title;

    /**
     * Button constructor.
     * @param $type
     * @param $title
     */
    public function __construct(string $title, string $type)
    {
        $this->type = $type;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
}