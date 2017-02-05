<?php

/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 26/10/2016
 * Time: 22:44
 */

class Attachment
{

    protected $type;
    protected $reusable;

    public function __construct($type, array $params)
    {
        $this->type = $type;
        if (in_array($type, ['video', 'audio', 'image', 'file']) && isset($params['url'])) {
            $this->url = $params['url'];
        } elseif ('location' === $type && isset($params['lat']) && isset($params['long'])) {
            $this->lat = $params['lat'];
            $this->long = $params['long'];
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }

    function __set($name, $value)
    {
        if(isset($this->name)) {
            $this->name = $value;
        }
    }
}