<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;

class Weather
{
    private $lang;
    private $unity;
    
    public function __construct($lang = "fr", $unity = "metric")
    {
        $this->lang = $lang;
        $this->unity = $unity;
        $this->client = new Client(['base_uri' => 'http://api.openweathermap.org/data/2.5/weather']);
    }
    
    public function getWeatherByCity($city)
    {
        $uri = "?q=" . $city . "&lang=" . $this->lang . "&unity =" . $this->unity;
        
        try {
            $response = $this->client->get($uri);
            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            return $response;
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
    
    public function getWeatherByGeographicCoordinates($lat, $lon)
    {
        $uri = "?lat=" . $lat . "&lon =" . $lon . "&lang=" . $this->lang . "&unity =" . $this->unity;
        
        try {
            $response = $this->client->get($uri);
            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            return $response;
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}

