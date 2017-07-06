<?php
/**
 *
 */

namespace AppBundle\Service;


use AppBundle\Entity\Wit\WitResponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use GuzzleHttp\Client;
use AppBundle\Event\ApiEvent;

class WitService
{

    private $apiKey;
    private $dispatcher;
    const NAME = 'WIT';
    private $client;
    private $em;
    const API_VERSION = '06/07/2017';

    /**
     * WitService constructor.
     * @param $apiKey
     * @param EntityManager $em
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct($apiKey, EntityManager $em, EventDispatcherInterface $dispatcher)
    {
        $this->apiKey = $apiKey;
        $this->dispatcher = $dispatcher;
        $this->em = $em;
        $this->client = new Client(['base_uri' => 'https://api.wit.ai/message']);
    }

    public function handleMessage(string $message)
    {
        $witResponse = $this->getWitAnalysis($message);
        $this->em->persist($witResponse);
        $this->em->flush();
    }

    /**
     * Send request to wit.ai API
     *
     * @param string $message
     * @return WitResponse
     */
    private function getWitAnalysis(string $message)
    {
        $params = http_build_query([
            'v' => self::API_VERSION,
            'q' => $message
        ]);

        try {
            $response = $this->client->get('message', [
                'query' => $params,
                'headers' => [
                    'Authorization' => "Bearer $this->apiKey"
                ]
            ]);

            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            $this->dispatcher->dispatch(ApiEvent::NAME, new ApiEvent(self::NAME, $response->getStatusCode()));

            $body = json_decode($response->getBody(), true);

            return new WitResponse($body);

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}