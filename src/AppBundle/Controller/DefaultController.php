<?php

namespace AppBundle\Controller;

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

        $message = $this->createMessageFromBody($request->getContent());

        $id = $message->getSender();
        $text = $message->getText();

        error_log("[Message Received][" . $message->getDate()->format('d-m-Y H:i:s') . "] Sender : " . $id . ", message : " . $text);

        $messageSenderService = $this->container->get('app.message_sender');
        $messageSenderService->sendTypingOn($id);
        $messageSenderService->sendShortText('echo : ' . $text, $id);

        return new Response();
    }

    /**
     * @param $body
     * @return Message
     */
    private function createMessageFromBody($body)
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
