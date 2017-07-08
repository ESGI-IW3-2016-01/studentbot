<?php

namespace AppBundle\Service;

use AppBundle\Event\ApiEvent;
use GuzzleHttp\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class YesOrNo
{
    const NAME = 'yesorno';

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->dispatcher = $eventDispatcher;
        $this->client = new Client(['base_uri' => 'http://yesno.wtf/']);
    }

    public function yesOrNo()
    {
        try {
            $response = $this->client->get("api/");
            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            $this->dispatcher->dispatch(ApiEvent::NAME, new ApiEvent(self::NAME, $response->getStatusCode()));
            return $response->getBody();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}

