<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facebook\SendMessage;
use AppBundle\Service\MessageSender;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Facebook\Message;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..'),
        ]);
    }

    /**
     * @Route("/webhook", name="webhook")
     */
    public function webhookAction(Request $request)
    {
        if ($request->query->has('hub_challenge')) {
            return new Response($request->query->get('hub_challenge'));
        }

        $message = $this->createMessageRecievedFromBody($request->getContent());
        error_log("[Message Received][" . $message->getDate()->format('d-m-Y H:i:s') . "] Sender : " . $message->getSender() . ", message : " . $message->getText());
        $responseMessage = new SendMessage($message->getSender(),$message->getText());

        /** @var MessageSender $messageSenderService */
        $messageSenderService = $this->container->get('app.message_sender');
        $messageSenderService->sendTypingOn($message->getSender());
        $messageSenderService->sendMessage($responseMessage);

        return new Response();
    }

    /**
     * @param $body
     * @return Message
     */
    private function createMessageRecievedFromBody($body)
    {
        $body = json_decode($body, true);
        $message = $body['entry'][0];

        return new Message(
            $message['id'],
            $message['messaging'][0]['sender']['id'],
            $message['messaging'][0]['recipient']['id'],
            $message['messaging'][0]['message']['text'],
            $message['time'],
            $message['messaging'][0]['message']['mid'],
            $message['messaging'][0]['message']['seq']
        );
    }
}
