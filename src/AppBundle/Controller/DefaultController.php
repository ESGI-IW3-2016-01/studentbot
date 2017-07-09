<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facebook\SendMessage;
use AppBundle\Service\MessageSender;
use AppBundle\Service\WitService;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Facebook\Message;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    use TraitQuestionAnswer;
    use TraitFootball;
    use TraitBasket;
    use TraitEsport;
    use TraitWeather;
    use TraitYoutube;
    use TraitYesOrNo;
    use TraitNews;
    use TraitCalendar;

    private $image;
    private $textAndImage;
    private $apiService;

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('index/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..'),
        ]);
    }

    /**
     * @Route("/webhook", name="webhook")
     * @param Request $request
     * @return Response
     */
    public function webhookAction(Request $request)
    {
        $this->apiService = $this->container->get('app.api_service');
        if ($request->query->has('hub_challenge')) {
            return new Response($request->query->get('hub_challenge'));
        }

        /** @var Logger $logger */
        $logger = $this->get('logger');
        $logger->error($request->getContent(), ['sender_faceboo_id' => null]);

        $message = $this->createMessageRecievedFromBody($request->getContent());

        /** @var WitService $witService */
        if ($this->apiService->getApi('WIT')) {
            $witService = $this->container->get('app.wit_service');
            $witService->handleMessage($message->getText());
        }

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
                case strstr($message->getPayload(), 'SCHOOL'):
                    $em = $this->getDoctrine()->getManager();
                    $user = $this->getUser();
                    $user->setSchool((int) str_replace("SCHOOL_", "", $message->getPayload()));
                    $em->persist($user);
                    $em->flush();
                    $messageSenderService->sendShortText("Ton école est enregistré", $message->getSender());
                    break;
                case strstr($message->getPayload(), 'STUDENT_GROUP'):
                    $em = $this->getDoctrine()->getManager();
                    $user = $this->getUser();
                    $user->setGroup((int) str_replace("STUDENT_GROUP_", "", $message->getPayload()));
                    $em->persist($user);
                    $em->flush();
                    $messageSenderService->sendShortText("Ta classe est enregistré", $message->getSender());
                    break;
            }
        } else {
            $question = $message->getText();

            $res = $this->questionAnswer($question);
            if (!$res) {
                $res = $this->choiceAPI($question, $message->getSender());
            }
            
            if ($res=="school") {
                $schoolService = $this->container->get('app.school_service');
                $messageSenderService->sendQuickReply($schoolService->getQuickRepliesForSchools(), "Choisi ton école", $message->getSender());
            } elseif ($res=="class") {
                $studentGroupService = $this->container->get('app.student_group_service');
                $messageSenderService->sendQuickReply($studentGroupService->getQuickRepliesForPromotions(), "Choisi ta classe", $message->getSender());
            }

            if (!is_array($res)) {
                $res = [$res];
            }
            foreach ($res as $resMessage) {
                $isMessageWithImage = explode("\xF0\x9F\x93\xB7",$resMessage);
                if (count($isMessageWithImage) != 1) {
                    $responseMessage = new SendMessage($message->getSender(), $isMessageWithImage[0]);
                    $messageSenderService->sendMessage($responseMessage);

                    $responseMessageWithImage = new SendMessage($message->getSender(), null, null, $isMessageWithImage[1]);
                    $messageSenderService->sendMessage($responseMessageWithImage);
                } else {
                    if ($this->image) {
                        $responseMessage = new SendMessage($message->getSender(), null, null, $resMessage);
                    } else {
                        $responseMessage = new SendMessage($message->getSender(), $resMessage);
                    }
                    $messageSenderService->sendMessage($responseMessage);
                }
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
        $message = $body['entry'][0]; // TODO : not safe enought

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

    private function choiceAPI($chaine, $current_user)
    {
        $apiService = $this->container->get('app.api_service');
        $this->image = false;
        $chaine = strtolower($chaine);

        switch ($chaine) {
            case "résultat football" :
            case strcmp("\xe2\x9a\xbd", $chaine) == 0 :
                if ($this->apiService->getApi('FOOTBALL')) {
                    $res = $this->football();
                }
                break;
            case "résultat basket" :
            case "résultat nba" :
            case strcmp("\xf0\x9f\x8f\x80", $chaine) == 0 :
                if ($this->apiService->getApi('BASKET')) {
                    $res = $this->basket();
                }
                break;
            case count(explode("\xF0\x9F\x8E\xAE", $chaine)) != 1 :
                if ($this->apiService->getApi('ESPORT')) {
                    $res = $this->esport($chaine);
                }
                break;
            case count(explode("\xE2\x98\x80", $chaine)) != 1 :
                if ($this->apiService->getApi('WEATHER')) {
                    $res = $this->weather(explode("\xE2\x98\x80", $chaine)[1]);
                }
                break;
            case count(explode("\xf0\x9f\x8e\xbc", $chaine)) != 1 :
                if ($this->apiService->getApi('YOUTUBE')) {
                    $res = $this->youtube($chaine);
                }
                break;
            case "yes or no ?" :
                if ($this->apiService->getApi('YESORNO')) {
                    $res = $this->yesOrNo();
                }
                $this->image = true;
                break;
            case "agenda":
            case "calendar":
            case "planning":
                $res = $this->calendar();
                break;
            case strstr($chaine, 'planning') :
            case strstr($chaine, 'agenda') :
                $res = $this->calendar($chaine, $current_user);
                break;
            case strcmp("\xF0\x9F\x93\xB0",$chaine) == 0 :
                if ($this->apiService->getApi('NEWS')) {
                    $res = $this->news($chaine);
                }
                $this->textAndImage = true;
                break;
            default :
                $res = "Désolé, je ne comprend pas encore tout... \xF0\x9F\x98\x95";
                break;
        }
        return $res;
    }

    /**
     * @Route("/public/choisir-locale/{locale}", name="choose_language")
     * @param Request $request
     * @return Response
     */
    public function chooseLanguageAction(Request $request, $locale = null)
    {
        $r = $this->get('request_stack')->getCurrentRequest();

        if ($locale != null) {
            // On enregistre la locale en session
            $this->get('session')->set('_locale', $locale);
            $r->setLocale($this->get('session')->get('_locale'));
            $this->get('translator')->setLocale($locale);
        }

        // on tente de rediriger vers la page d'origine
        $url = $request->headers->get('referer');

        if (empty($url)) {
            $url = $this->container->get('router')->generate('homepage');
        }

        return $this->redirect($url);
    }
}
