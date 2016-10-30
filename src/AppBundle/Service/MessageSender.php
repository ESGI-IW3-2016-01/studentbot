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

    public function sendAction(SenderAction $action, $recipient)
    {
        $container = $this->getContainer();
        $uri = $container->getParameter('facebook.message_uri');
        $token = $container->getParameter('facebook.page_access_token');

        $client = new Client(['base_uri' => 'https://graph.facebook.com/v2.8/me']);
        $response = $client->request('POST', '/messages',
            [
                'headers' =>
                    [
                        'content-type' => 'application/json'
                    ],
                'query' => 'access_token=' . $token,
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