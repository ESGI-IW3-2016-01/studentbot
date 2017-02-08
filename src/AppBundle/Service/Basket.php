<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;

class Basket
{
    private $basket_key;
    private $manager;

    public function __construct(EntityManager $manager, $basketKey)
    {
        $this->basket_key = $basketKey;
        $this->manager = $manager;
        $this->client = new Client(['base_uri' => 'http://api.sportradar.us/nba-t3/']);
    }
    
    public function getResultNBA() 
    {
        $date = date("Y/m/d");
        
        $uri = "games/" . $date . "/schedule.json?api_key=" . $this->basket_key;
            
        try {
            $response = $this->client->get($uri);
            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            return $response->getBody();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}