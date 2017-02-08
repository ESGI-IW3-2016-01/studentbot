<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;

class YesOrNo 
{
    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://yesno.wtf/api/']);
    }
    
    public function yesOrNo() 
    { 
        try {
            $response = $this->client->get();
            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            return $response->getBody();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}

