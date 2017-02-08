<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facebook\SendMessage;
use AppBundle\Service\MessageSender;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Facebook\Message;
use AppBundle\Service\Basket;
use AppBundle\Service\Football;
use AppBundle\Service\Weather;
use AppBundle\Controller\footballControllerTrait;

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

        /** @var MessageSender $messageSenderService */
        $messageSenderService = $this->container->get('app.message_sender');
        $messageSenderService->sendTypingOn($message->getSender());

        if ($message->hasPayload()) {
            switch ($message->getPayload()) {
                case "BUTTON_HELP":
                    $messageSenderService->sendShortText("Tu as besoin d'aide ?",$message->getSender());
                    break;
                case "BUTTON_RESET":
                    $messageSenderService->sendShortText("Tu veux tout reset ?",$message->getSender());
                    break;
            }
        } else {
            $res = $this->choiceAPI($message->getText());
            
            if (is_array($res)) {
                foreach ($res as $resMessage) {
                    $responseMessage = new SendMessage($message->getSender(), $resMessage);
                    $messageSenderService->sendMessage($responseMessage);
                }
            }else {
                $responseMessage = new SendMessage($message->getSender(), $res);
                $messageSenderService->sendMessage($responseMessage);
            }
            
            
        }

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

        if (isset($message['messaging'][0]['postback']['payload'])) {
            $messageObject = new Message(
                $message['id'],
                $message['messaging'][0]['sender']['id'],
                $message['messaging'][0]['recipient']['id'],
                null,
                $message['time'],
                null,
                null
            );
            $messageObject->setPayload($message['messaging'][0]['postback']['payload']);
        } else {
            $messageObject = new Message(
                $message['id'],
                $message['messaging'][0]['sender']['id'],
                $message['messaging'][0]['recipient']['id'],
                $message['messaging'][0]['message']['text'],
                $message['time'],
                $message['messaging'][0]['message']['mid'],
                $message['messaging'][0]['message']['seq']
            );
        }

        return $messageObject;
    }
    
    private function choiceAPI($chaine) 
    {
        switch ($chaine){
            case "\xE2\x9A\xBD" :
                $res =$this->football();
                break;
            default :
                $res = $chaine;
        }
        
        return $res;
    }
}
