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
    private $image;

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
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
            case count(explode("météo", $chaine)) != 1 :
                $res = $this->weather(explode("météo", $chaine)[1]);
                break;
            case "yes or no ?" :
                $yesOrNo = new \AppBundle\Service\YesOrNo();
                $json_data = $yesOrNo->yesOrNo();
                $data = json_decode($json_data);
                $res = $data->image;
                $this->image = true;
                break;
            default :
                $res = "Désolé, je ne comprend pas encore tout... \xF0\x9F\x98\x95";
                break;
        }

        return $res;
    }

    private function basket()
    {
        /** @var Basket $basket */
        $basket = $this->container->get('app.basket_api_service');
        $json_data = $basket->getResultNBA();

        $data = json_decode($json_data);

        $res = [];
        foreach ($data->games as $games) {
            $home_team = $games->home->name;
            $away_team = $games->away->name;
            $status = $status = $this->getStatusNBA($games->status);


            if ($games->status == "closed") {
                $home_points = $games->home_points;
                $away_points = $games->away_points;

                $res[] = $status . " - " . $away_team . " " . $away_points . " - " . $home_points . " " . $home_team;
            } else {
                $res[] = $status . " - " . $away_team . " vs " . $home_team;
            }
        }

        return $res;
    }

    private function getStatusNBA($status)
    {
        switch ($status) {
            case 'closed':
                $res = "Closed \xf0\x9f\x94\x92";
                break;
            case 'scheduled':
                $res = "Scheduled \xf0\x9f\x93\x85";
                break;
            case 'in progress':
                $res = "In progress \xe2\x8f\xb3";
                break;
            default:
                $res = $status;
                break;
        }

        return $res;
    }

    private function football()
    {
        /** @var Football $football */
        $football = $this->container->get('app.football_api_service');
        $json_data = $football->getResultFootball();

        $data = json_decode($json_data);

        $res = [];
        foreach ($data->results as $result) {
            $home_team = $result->sport_event->competitors[0]->name;
            $away_team = $result->sport_event->competitors[1]->name;
            $home_score = $result->sport_event_status->home_score;
            $away_score = $result->sport_event_status->away_score;
            $tournament = $result->sport_event->tournament->name;

            $flag = $this->getTournamentFlag($tournament);

            $str = $tournament . " " . $flag . " - " . $home_team . " " . $home_score . " - " . $away_score . " " . $away_team;
            $res[] = $str;
        }

        return $res;
    }

    private function getTournamentFlag($codeFlag)
    {
        switch ($codeFlag) {
            case 'LaLiga Santander':
                $flag = "\xF0\x9F\x87\xAA\xF0\x9F\x87\xB8";
                break;
            case 'Eredivisie':
                $flag = "\xf0\x9f\x87\xb3\xf0\x9f\x87\xb1";
                break;
            case 'Serie A':
                $flag = "\xF0\x9F\x87\xAE\xF0\x9F\x87\xB9";
                break;
            case 'Super League':
                $flag = "\xf0\x9f\x87\xac\xf0\x9f\x87\xb7";
                break;
            case 'Premier League':
                $flag = "\xF0\x9F\x87\xAC\xF0\x9F\x87\xA7";
                break;
            case 'Division 1A':
                $flag = "\xf0\x9f\x87\xa7\xf0\x9f\x87\xaa";
                break;
            case 'Serie B':
                $flag = "\xF0\x9F\x87\xAE\xF0\x9F\x87\xB9";
                break;
            case 'Bundesliga':
                $flag = "\xF0\x9F\x87\xA9\xF0\x9F\x87\xAA";
                break;
            case 'Primeira Liga':
                $flag = "\xf0\x9f\x87\xb5\xf0\x9f\x87\xb9";
                break;
            case 'Ligue 1':
                $flag = "\xF0\x9F\x87\xAB\xF0\x9F\x87\xB7";
                break;
            default:
                $flag = " ";
                break;
        }

        return $flag;
    }

    private function weather($city)
    {
        $weather = $this->container->get('app.weather_api_service');
        $json_data = $weather->getWeatherByCity($city);

        $data = json_decode($json_data);

        $res = "Météo " . $data->name . " : " . $data->weather[0]->description . " | Température " . round($data->main->temp) . "°C";

        return $res;
    }
}
