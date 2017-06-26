<?php

namespace AppBundle\Service;

use AppBundle\Event\ApiEvent;
use GuzzleHttp\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Football
{
    private $csgo_key;
    private $dota_key;
    private $lol_key;
    private $lang;
    private $dispatcher;
    const NAME = "esport";

    public function __construct($csgoKey, $dotaKey, $lolKey, $lang, EventDispatcherInterface $dispatcher)
    {
        $this->csgo_key = $csgoKey;
        $this->dota_key = $dotaKey;
        $this->lol_key = $lolKey;
        $this->lang = $lang;
        $this->dispatcher = $dispatcher;
        $this->client = new Client(['base_uri' => 'https://api.sportradar.us/']);
    }

    public function getResultCsgo() {
        $date = date("Y/m/d");
        $uri = 'csgo-ti/'. $this->lang .'/schedules/'. $date.  '/results.json?api_key=' . $this->csgo_key;

        return $this->getResult($uri,'csgo');
    }

    public function getResultDota() {
        $date = date("Y/m/d");
        $uri = 'dota2-ti/'. $this->lang .'/schedules/'. $date.  '/results.json?api_key=' . $this->dota_key;

        return $this->getResult($uri,'dota');
    }

    public function getResultLol() {
        $date = date("Y/m/d");
        $uri = 'lol-ti/'. $this->lang .'/schedules/'. $date.  '/results.json?api_key=' . $this->dota_key;

        return $this->getResult($uri,'lol');
    }

    private function getResult($uri, $name) {
        try {
            $response = $this->client->get($uri);
            error_log('[Guzzle Response Esport '. $name . ']'. $response->getStatusCode() . ' : ' . $response->getBody());
            $this->dispatcher->dispatch(ApiEvent::NAME, new ApiEvent(self::NAME, $response->getStatusCode()));
            return $response->getBody();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}
