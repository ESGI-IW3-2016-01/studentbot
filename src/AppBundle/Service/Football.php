<?php

namespace AppBundle\Service;

use AppBundle\Entity\ApiLog;
use AppBundle\Repository\ApiLogRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;

class Football
{
    private $football_key;
    private $manager;

    public function __construct(EntityManager $manager, $footballKey)
    {
        $this->football_key = $footballKey;
        $this->manager = $manager;
        $this->client = new Client(['base_uri' => 'https://api.sportradar.us/soccer-t3/eu/fr/']);
    }

    public function getResultFootball()
    {
        $date = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
        $uri = "schedules/" . $date . "/results.json?api_key=" . $this->football_key;

        try {
            $response = $this->client->get($uri);
            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            // TODO Faire un event

            $apilog = new ApiLog();
            $apilog->setCode($response->getStatusCode());
            $apilog->setDate(new Datetime());
            $apilog->setFacebookId(null);
            $apilog->setApi('football');
            $apilog->setAction(null);

            $this->manager->persist($apilog);
            $this->manager->flush();

            return $response->getBody();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}
