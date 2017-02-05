<?php

namespace AppBundle\Service;

/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 30/10/2016
 * Time: 11:50
 */

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use AppBundle\Entity\Facebook\Message;

class MessageSender
{

    protected $token;
    protected $graph;

    protected $client;

    /**
     * MessageSender constructor.
     * @param string $token
     * @param string $graph
     */
    public function __construct($token, $graph = 'v2.6')
    {
        $this->token = $token;
        $this->graph = $graph;

        $this->client = new Client(['base_uri' => 'https://graph.facebook.com/']);
    }

    /**
     * @param string $action
     * @param string $recipient
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    private function sendAction($action, $recipient)
    {
        $body = ['recipient' => ['id' => $recipient], "sender_action" => $action];
        $uri = $this->graph . '/me/messages';
        try {
            $response = $this->client->post($uri, ['json' => $body, 'query' => ['access_token' => $this->token]]);
            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            return $response;
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function sendMarkSeen($recipient)
    {
        $this->sendAction("mark_seen", $recipient);
    }

    public function sendTypingOn($recipient)
    {
        $this->sendAction("typing_on", $recipient);
    }

    public function sendTypingOff($recipient)
    {
        $this->sendAction("typing_off", $recipient);
    }

    public function sendShortText($text, $recipient)
    {
        $body = ['recipient' => ['id' => $recipient], "message" => ["text" => $text]];
        $uri = $this->graph . '/me/messages';
        try {
            $response = $this->client->post($uri, ['json' => $body, 'query' => ['access_token' => $this->token]]);

            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());

            return $response;

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * @param SendMessage $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendMessage(SendMessage $message)
    {
        $body = ['recipient' => ['id' => $message->getRecipient()], "message" => ["text" => $message->getText()]];
        $uri = $this->graph . '/me/messages';
        try {
            $response = $this->client->post($uri, ['json' => $body, 'query' => ['access_token' => $this->token]]);

            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());

            return $response;

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}