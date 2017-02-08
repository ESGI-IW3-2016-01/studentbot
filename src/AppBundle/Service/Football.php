<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;

class Football extends ContainerAwareCommand
{   
    private $football_key;
    public function __construct()
    {
        $container = $this->getContainer();
        $this->football_key = $container->getParameter('football_key');
        $this->client = new Client(['base_uri' => 'https://api.sportradar.us/soccer-t3/eu/fr/']);
    }
    
    public function getResultFootball() 
    {
        $date = date ("Y-m-d", mktime (0,0,0,date('m'),date('d')-1,date('Y')));
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
            /** @var EntityManager $em */
            $em = $this->getContainer()->get('Doctrine')->getManager();
            /** @var ApiRepository $repo */
            $repo = $em->getRepository('AppBundle\Entity\ApiLog');
            $em->persist($apilog);
            $em->flush();
            return $response->getBody();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}
