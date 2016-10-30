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

class MessageSender
{

    protected $token;
    protected $graph;

    /**
     * MessageSender constructor.
     */
    public function __construct($token, $graph)
    {
        $this->token = $token;
        $this->graph = $graph;
    }

    public function sendAction($action, $recipient)
    {

        $client = new Client(['base_uri' => 'https://graph.facebook.com/me']);
        $response = $client->request('POST', '/messages',
            [
                'headers' =>
                    [
                        'content-type' => 'application/json'
                    ],
                'query' => 'access_token=' . $this->token,
                'json' => [
                    'recipient' => ['id' => $recipient],
                    'sender_action' => $action
                ]
            ]);

    }

    public function sendSimpleTextAction()
    {

    }

}