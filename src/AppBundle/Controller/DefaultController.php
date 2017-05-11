<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facebook\Attachment;
use AppBundle\Entity\Facebook\SendMessage;
use AppBundle\Service\MessageSender;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Facebook\Message;
use AppBundle\Service\Basket;
use AppBundle\Service\Football;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    use TraitFootball;
    use TraitBasket;
    use TraitWeather;
    use TraitYoutube;
    use TraitYesOrNo;
    
    private $image;

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // session_destroy();
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
                    $messageSenderService->sendShortText("Tu as besoin d'aide ?", $message->getSender());
                    break;
                case "BUTTON_RESET":
                    $messageSenderService->sendShortText("Tu veux tout reset ?", $message->getSender());
                    break;
            }
        } else {
            $res = $this->choiceAPI($message->getText());
            if (!is_array($res)) {
                $res = [$res];
            }
            foreach ($res as $resMessage) {
                if ($this->image) {
                    $responseMessage = new SendMessage($message->getSender(), null, null, $resMessage);
                } else {
                    $responseMessage = new SendMessage($message->getSender(), $resMessage);
                }
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
        $this->image = false;
        $chaine = strtolower($chaine);
        switch ($chaine) {
            case "résultat football" :
            case strcmp("\xe2\x9a\xbd", $chaine) == 0 :
                $res = $this->football();
                break;
            case "résultat basket" :
            case "résultat nba" :
            case strcmp("\xf0\x9f\x8f\x80", $chaine) == 0 :
                $res = $this->basket();
                break;
            case count(explode("\xE2\x98\x80", $chaine)) != 1 :
                $res = $this->weather(explode("\xE2\x98\x80", $chaine)[1]);
                break;
            case count(explode("\xf0\x9f\x8e\xbc", $chaine)) != 1 :
                $res = $this->youtube($chaine);
                break;
            case "yes or no ?" :
                $res = $this->yesOrNo();
                $this->image = true;
                break;
            default :
                $res = "Désolé, je ne comprend pas encore tout... \xF0\x9F\x98\x95";
                break;
        }

        return $res;
    }
}
