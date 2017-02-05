<?php

namespace AppBundle\Entity\Facebook;


class MenuItem
{

    private $type;
    private $title;
    private $url;
    private $payload;
    private $height;

    /**
     * MenuItem constructor.
     * @param $type
     * @param $title
     * @param $url
     * @param $payload
     * @param $height
     */
    public function __construct($title, $type = 'web_url', $url = null, $payload = null, $height = 'tall')
    {
        $this->type = $type;
        $this->title = $title;
        $this->url = $url;
        $this->payload = $payload;
        $this->height = $height;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function toArray() {
        $tmp = [
            'type' => $this->type,
            'title' => $this->title
        ];
        if($this->type == 'web_url') {
            $tmp['url'] = $this->url;
        } elseif ($this->type == 'postback') {
            $tmp['payload'] = $this->payload;
        }
        return $tmp;
    }

    public function __toString() {
        return get_class($this) . ':' . $this->title . ':' . $this->type;
    }
}