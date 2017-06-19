<?php
namespace AppBundle\Controller;

trait TraitFootball
{
    private function football()
    {
        /** @var Football $football */
        $football = $this->container->get('app.football_api_service');
        $jsonData = $football->getResultFootball();

        $data = json_decode($jsonData);

        if (!isset($data->results)) {
            $res = "Aucun résultat pour cette date.";
            return $res;
        }

        $res = [];
        foreach ($data->results as $result) {
            $home_team = $result->sport_event->competitors[0]->name;
            $away_team = $result->sport_event->competitors[1]->name;
            $home_score = $result->sport_event_status->home_score;
            $away_score = $result->sport_event_status->away_score;
            $tournament = $result->sport_event->tournament->name;

            $flag = $this->getTournamentFlag($tournament);

            $str = $tournament." ".$flag." - ".$home_team." ".$home_score." - ".$away_score." ".$away_team;
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
}
