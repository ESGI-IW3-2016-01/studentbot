<?php

namespace AppBundle\Service;

use AppBundle\Event\ApiEvent;
use GuzzleHttp\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Football
{
    private $football_key;
    private $dispatcher;
    const NAME = "football";

    public function __construct($footballKey, EventDispatcherInterface $dispatcher)
    {
        $this->football_key = $footballKey;
        $this->dispatcher = $dispatcher;
        $this->client = new Client(['base_uri' => 'https://api.sportradar.us/soccer-t3/eu/fr/']);
    }

    public function getResultFootball()
    {
        //$date = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
        $date = "2017-04-18";
        $uri = "schedules/" . $date . "/results.json?api_key=" . $this->football_key;
         error_log($uri);
         
        try {
            $response = $this->client->get($uri);
            error_log('[Guzzle Response Football] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            $this->dispatcher->dispatch(ApiEvent::NAME, new ApiEvent(self::NAME, $response->getStatusCode()));
            return $response->getBody();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}
