<?php

namespace AppBundle\Service;

use AppBundle\Event\ApiEvent;
use GuzzleHttp\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Basket
{
    private $basket_key;
    private $dispatcher;
    const NAME = "basketball";

    public function __construct($basketKey, EventDispatcherInterface $dispatcher)
    {
        $this->basket_key = $basketKey;
        $this->dispatcher = $dispatcher;
        $this->client = new Client(['base_uri' => 'http://api.sportradar.us/nba-t3/']);
    }
    
    public function getResultNBA() 
    {   
        //$date = date("Y/m/d");
        $date = "2017/03/01";
        $uri = "games/" . $date . "/schedule.json?api_key=" . $this->basket_key;
            
        try {
            $response = $this->client->get($uri);
            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            $this->dispatcher->dispatch(ApiEvent::NAME, new ApiEvent(self::NAME, $response->getStatusCode()));
            return $response->getBody();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}