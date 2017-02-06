<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;

class Football
{   
    private $football_key;
    public function __construct()
    {
        $this->football_key = $this->container->getParameter('football_key');
        $this->client = new Client(['base_uri' => 'https://api.sportradar.us/soccer-t3/eu/fr/']);
    }
    
    public function getResultFootball() 
    {
        $date = date ("Y-m-d", mktime (0,0,0,date('m'),date('d')-1,date('Y')));
        $uri = "schedules/" . $date . "/results.json?api_key=" . $this->football_key;
         
        try {
            $response = $this->client->get($uri);
            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            return $response;
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}