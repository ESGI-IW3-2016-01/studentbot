<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ApiEvent extends Event
{
    const NAME = 'api.called';
    private $apiName;
    private $responseCode;

    public function __construct($apiName, $responseCode)
    {
        $this->apiName = $apiName;
        $this->responseCode = $responseCode;
    }

    /**
     * @return mixed
     */
    public function getApiName()
    {
        return $this->apiName;
    }

    /**
     * @param mixed $apiName
     */
    public function setApiName($apiName)
    {
        $this->apiName = $apiName;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param mixed $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }
}