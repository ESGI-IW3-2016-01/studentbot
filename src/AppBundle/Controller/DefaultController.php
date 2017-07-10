<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facebook\Attachment;
use AppBundle\Entity\Facebook\SendMessage;
use AppBundle\Entity\School\School;
use AppBundle\Entity\User;
use AppBundle\Service\ApiService;
use AppBundle\Service\MessageSender;
use AppBundle\Service\SchoolService;
use AppBundle\Service\StudentGroupService;
use AppBundle\Service\UserService;
use AppBundle\Service\WitService;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\Group;
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
    use TraitSummaryFeature;

    private $image;
    private $textAndImage;

    /**
     * @var ApiService $apiService
     */
    private $apiService;

    /**
     * @var MessageSender $messageSenderService
     */
    private $messageSenderService;

    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * @var Logger $logger
     */
    private $logger;

    /**
     * @var UserService $userService
     */
    private $userService;

    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function indexAction()
    {
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
        if ($request->query->has('hub_challenge')) {
            return new Response($request->query->get('hub_challenge'));
        }

        $this->em = $this->getDoctrine()->getManager();

        $this->apiService = $this->container->get('app.api_service');
        $this->messageSenderService = $this->container->get('app.message_sender');
        $this->userService = $this->container->get('app.user_service');

        $this->logger = $this->get('logger');
        $this->logger->error($request->getContent(), ['sender_facebook_id' => null]);

        /** @var Message $message */
        $message = $this->createMessageRecievedFromBody($request->getContent());

        $this->messageSenderService->sendTypingOn($message->getSender());

        if ($message->hasPayload()) {
            switch ($message->getPayload()) {
                case 'MENU_ITEM_RESET_PAYLOAD':
                    $this->messageSenderService
                        ->sendShortText('Tu veux tout reset ?', $message->getSender());
                    break;
                case 'MENU_ITEM_HELP_PAYLOAD':
                case 'MENU_ITEM_FONCTIONNALITS_PAYLOAD':
                case 'MENU_ITEM_FONCTIONNALITES_PAYLOAD':
                    $responses = $this->summaryFeature();
                    foreach ($responses as $response) {
                        $this->messageSenderService
                            ->sendShortText($response, $message->getSender());
                    }
                    break;
                case strstr($message->getPayload(), 'SCHOOL'):

                    /** @var User $user */
                    $user = $this->userService->handleUser($message->getSender());

                    $schoolId = (int)str_replace('SCHOOL_', '', $message->getPayload());
                    /** @var School $school */
                    $school = $this->em
                        ->getRepository('AppBundle:School\School')
                        ->findOneBy(['id' => $schoolId]);

                    $user->setSchool($school);
                    $this->em->persist($user);
                    $this->em->flush();

                    $this->messageSenderService->sendShortText(
                        'Ton école est enregistrée',
                        $message->getSender()
                    );
                    break;
                case strstr($message->getPayload(), 'STUDENT_GROUP'):

                    /** @var User $user */
                    $user = $this->userService->handleUser($message->getSender());

                    $groupId = (int)str_replace('STUDENT_GROUP_', '', $message->getPayload());
                    /** @var Group $group */
                    $group = $this->em
                        ->getRepository('AppBundle:School\StudentGroup')
                        ->findOneBy(['id' => $groupId]);

                    $user->setStudentGroup($group);
                    $this->em->persist($user);
                    $this->em->flush();

                    $this->messageSenderService->sendShortText(
                        'Ta classe est enregistré',
                        $message->getSender());
                    break;
            }
        } elseif ($message->hasText()) {

            /** @var SchoolService $schoolService */
            $schoolService = $this->container->get('app.school_service');
            /** @var StudentGroupService $studentGroupService */
            $studentGroupService = $this->container->get('app.student_group_service');

            $question = $message->getText();

            /** @var WitService $witService */
            if ($this->apiService->getApi('WIT')) {
                $witService = $this->container->get('app.wit_service');
                $witService->handleMessage($question);
            }

            $res = $this->questionAnswer($question);
            if (!$res) {
                $res = $this->choiceAPI($question, $message->getSender());
            }

            if ($res == 'school') {
                $this->messageSenderService->sendQuickReply(
                    $schoolService->getQuickRepliesForSchools(),
                    'Choisi ton école',
                    $message->getSender()
                );
                return new Response();
            } elseif ($res == 'class') {
                /** @var User $user */
                $user = $this->userService->handleUser($message->getSender());
                if ($user->getSchool()) {
                    $this->messageSenderService->sendQuickReply(
                        $studentGroupService->getQuickRepliesForGroups($user->getSchool()->getId()),
                        'Choisi ta classe',
                        $message->getSender()
                    );
                } else {
                    // TODO
                }
                return new Response();
            }

            if (!is_array($res)) {
                $res = [$res];
            }
            foreach ($res as $resMessage) {
                $isMessageWithImage = explode('\xF0\x9F\x93\xB7', $resMessage);
                if (count($isMessageWithImage) != 1) {
                    $responseMessage = new SendMessage($message->getSender(), $isMessageWithImage[0]);
                    $this->messageSenderService->sendMessage($responseMessage);

                    $responseMessageWithImage = new SendMessage($message->getSender(), null, null, $isMessageWithImage[1]);
                    $this->messageSenderService->sendMessage($responseMessageWithImage);
                } else {
                    if ($this->image) {
                        $responseMessage = new SendMessage($message->getSender(), null, null, $resMessage);
                    } else {
                        $responseMessage = new SendMessage($message->getSender(), $resMessage);
                    }
                    $this->messageSenderService->sendMessage($responseMessage);
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
        if(isset($body['entry'])) {
            $message = array_pop($body['entry']); // TODO : not safe enought
        }

        if (isset($message['messaging'][0]['postback']['payload'])) {
            // Payload message
            $messaging = $message['messaging'][0];
            $messageObject = new Message(
                $message['id'],
                $messaging['sender']['id'],
                $messaging['recipient']['id'],
                null,
                $messaging['timestamp']
            );
            $messageObject->setPayload($message['messaging'][0]['postback']['payload']);
        } elseif (isset($message['messaging'][0]['message']['quick_reply'])) {
            // Quick Reply Payload
            $messageObject = new Message(
                $message['id'],
                $message['messaging'][0]['sender']['id'],
                $message['messaging'][0]['recipient']['id'],
                null,
                $message['time'],
                $message['messaging'][0]['message']['mid'],
                $message['messaging'][0]['message']['seq']
            );
            $messageObject->setPayload($message['messaging'][0]['message']['quick_reply']['payload']);
        } elseif (isset($message['messaging'][0]['message']['attachments'])) {
            // Image Message
            $messageObject = new Message(
                $message['id'],
                $message['messaging'][0]['sender']['id'],
                $message['messaging'][0]['recipient']['id'],
                null,
                $message['time'],
                $message['messaging'][0]['message']['mid'],
                $message['messaging'][0]['message']['seq'],
                new Attachment($message['messaging'][0]['message']['attachments'][0]['type'],
                    ['url' => $message['messaging'][0]['message']['attachments'][0]['payload']['url']])
            );
        } else {
            // Text message
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
        $res = "Désolé, je ne comprend pas encore tout... \xF0\x9F\x98\x95";

        switch ($chaine) {
            case 'résultat football' :
            case strcmp("\xe2\x9a\xbd", $chaine) == 0 :
                if ($this->apiService->getApi('FOOTBALL')) {
                    $res = $this->football();
                }
                break;
            case 'résultat basket' :
            case 'résultat nba' :
            case strcmp("\xf0\x9f\x8f\x80", $chaine) == 0 :
                if ($this->apiService->getApi('BASKETBALL')) {
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
            case 'yes or no ?':
                if ($this->apiService->getApi('YESORNO')) {
                    $res = $this->yesOrNo();
                }
                $this->image = true;
                break;
            case strcmp("\xF0\x9F\x93\x85", $chaine) == 0 :
            case 'agenda':
            case 'calendar':
            case 'planning':
                $res = $this->calendar(null, $current_user);
                break;
            case strstr($chaine, 'planning') :
            case strstr($chaine, 'agenda') :
                $res = $this->calendar($chaine, $current_user);
                break;
            case strcmp("\xF0\x9F\x93\xB0", $chaine) == 0 :
                if ($this->apiService->getApi('NEWS')) {
                    $res = $this->news();
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
     * @param null $locale
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
