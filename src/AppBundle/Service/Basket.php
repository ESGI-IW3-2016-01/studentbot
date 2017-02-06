<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;

class Basket
{   
    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://api.sportradar.us/nba-t3/']);
    }
    
    public function getResultNBA() 
    {
        $date = date("Y/m/d");
        
        $uri = "games/" . $date . "/schedule.json?api_key=" . BASKET_KEY;
            
        try {
            $response = $this->client->get($uri);
            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            return $response;
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}