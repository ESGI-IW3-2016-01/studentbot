<?php

namespace AppBundle\Service;

use AppBundle\Entity\Facebook\QuickReply;
use AppBundle\Entity\Facebook\QuickReplyResponse;
use GuzzleHttp\Client;
use AppBundle\Entity\Facebook\SendMessage;

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
     * @return null|\Psr\Http\Message\ResponseInterface
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
        return null;
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
        $body = [
            'recipient' => [
                'id' => $recipient
            ],
            'message' => [
                'text' => $text
            ]
        ];

        $this->send($body);

    }

    /**
     * @param SendMessage $message
     */
    public function sendMessage(SendMessage $message)
    {

        $body = [
            'recipient' => [
                'id' => $message->getRecipient()
            ]
        ];

        if ($message->getText() != null) {
            $body['message'] = ['text' => $message->getText()];
        }
        if ($message->getAttachment() != null) {
            $body['message'] = ['attachment' => ['type' => 'image', 'payload' => ['url' => $message->getAttachment()]]];
        }

        $this->send($body);
    }

    /**
     * @param $quickReplies array of QuickReply
     * @param $text
     * @param $recipient
     */
    public function sendQuickReply($quickReplies, $text, $recipient)
    {
        $array = [];
        if (count($quickReplies) > 0) {
            /** @var QuickReply $quickReply */
            foreach ($quickReplies as $quickReply) {
                $array[] = $quickReply->toArray();
            }

            $body = [
                'recipient' => [
                    'id' => $recipient
                ],
                'message' => [
                    'text' => $text,
                    'quick_replies' => $array
                ]
            ];

            $this->send($body);

        } else {
            //TODO : throw error
        }
    }

    /**
     * @param QuickReplyResponse $quickReplyResponse
     */
    public function sendQuickReplyResponse(QuickReplyResponse $quickReplyResponse)
    {
        $this->send($quickReplyResponse->toArray());
    }

    /**
     * Send message to Facebook
     * @param $body
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    private function send($body)
    {
        $uri = $this->graph . '/me/messages';
        try {
            $response = $this->client->post($uri, [
                'json' => $body,
                'query' => [
                    'access_token' => $this->token
                ]
            ]);

            error_log('[Guzzle Response]' . $response->getStatusCode() . ' : ' . $response->getBody());

            return $response;

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        return null;
    }
}