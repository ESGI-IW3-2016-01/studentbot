<?php
use AppBundle\Entity\ApiLog;
use Symfony\Component\EventDispatcher\Event;

class ApiEvent extends Event
{
    const NAME = 'api.called';

    private $log;

    public function __construct(ApiLog $apiLog)
    {
        $this->log = $apiLog;
    }

    public function getOrder()
    {
        return $this->log;
    }

}