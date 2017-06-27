<?php
namespace AppBundle\Controller;

trait TraitEsport
{
    private function esport($chaine)
    {
        /** @var Esport $esport */
        $tab = explode(" ", $chaine);
        $res = [];

        if (sizeof($tab) == 1 || $tab[0] != "\xF0\x9F\x8E\xAE") {
            $res[] = 'Voici les jeux dont les résultats sont disponibles :';
            $res[] = "\xF0\x9F\x8E\xAE csgo";
            $res[] = "\xF0\x9F\x8E\xAE dota2";
            $res[] = "\xF0\x9F\x8E\xAE lol";

            return $res;
        }

        $esport = $this->container->get('app.esport_api_service');

        $data = '';
        switch ($tab[1]) {
            case 'csgo':
                $data = json_decode($esport->getResultCsgo());
                break;
            case 'dota2':
            case 'dota':
                $data = json_decode($esport->getResultDota());
                break;
            case 'lol':
                $data = json_decode($esport->getResultLol());
                break;
            default:
                $res[] = 'Voici les jeux dont les résultats sont disponibles :';
                $res[] = "\xF0\x9F\x8E\xAE csgo";
                $res[] = "\xF0\x9F\x8E\xAE dota2";
                $res[] = "\xF0\x9F\x8E\xAE lol";

                return $res;
        }

        if (!isset($data->results) || sizeof($data->result) == 0) {
            $res = "Aucun résultat pour cette date.";
            return $res;
        }

        foreach ($data->results as $result) {
            $tournament_name = $result->sport_event->tournament->name;
            $season_name = $result->sport_event->season->name;
            $tournament_round = $result->sport_event->tournament_round->type . ' - ' . $result->sport_event->tournament_round->number . ' - ' . $result->sport_event->tournament_round->name;

            $home_team = '';
            if (isset($result->sport_event->competitors[0]->country)) {
                $home_team .= $this->getFlag($result->sport_event->competitors[0]->country_code) . ' ';
            }
            $home_team .= $result->sport_event->competitors[0]->name;

            $away_team = '';
            if (isset($result->sport_event->competitors[1]->country)) {
                $away_team .= $this->getFlag($result->sport_event->competitors[1]->country_code) . ' ';
            }
            $away_team .= $result->sport_event->competitors[1]->name;


            if ($result->sport_event_status->status == 'closed') {
                $home_score = $result->sport_event_status->home_score;
                $away_score = $result->sport_event_status->away_score;

                $score_period = '';
                foreach ($result->sport_event_status->period_scores as $score) {
                    $score_period .= $score->home_score. '-' . $score->away_score. ' ';
                }

                $str = $tournament_name. ' ' . $season_name . ' ' . $tournament_round . ' | ' . $home_team . ' ' . $home_score . ' - ' . $away_score . ' ' . $away_team. ' score detail : ' . $score_period;
            } else {
                $status = $result->sport_event_status->status;
                $str = $tournament_name. ' ' . $season_name . ' ' . $tournament_round . ' | ' . $home_team . ' '. $status . ' '. $away_team;
            }

            $res[] = $str;
        }

        return $res;
    }

    private function getFlag($codeFlag)
    {
        switch ($codeFlag) {
            case 'ESP':
                $flag = "\xF0\x9F\x87\xAA\xF0\x9F\x87\xB8";
                break;
            case 'NLD':
                $flag = "\xf0\x9f\x87\xb3\xf0\x9f\x87\xb1";
                break;
            case 'ITA':
                $flag = "\xF0\x9F\x87\xAE\xF0\x9F\x87\xB9";
                break;
            case 'CHE':
                $flag = "\xf0\x9f\x87\xac\xf0\x9f\x87\xb7";
                break;
            case 'GBR':
                $flag = "\xF0\x9F\x87\xAC\xF0\x9F\x87\xA7";
                break;
            case 'BEL':
                $flag = "\xf0\x9f\x87\xa7\xf0\x9f\x87\xaa";
                break;
            case 'RUS':
                $flag = "\xF0\x9F\x87\xB7\xF0\x9F\x87\xBA";
                break;
            case 'DEU':
                $flag = "\xF0\x9F\x87\xA9\xF0\x9F\x87\xAA";
                break;
            case 'PRT':
                $flag = "\xf0\x9f\x87\xb5\xf0\x9f\x87\xb9";
                break;
            case 'FRA':
                $flag = "\xF0\x9F\x87\xAB\xF0\x9F\x87\xB7";
                break;
            case 'USA':
                $flag = "\xF0\x9F\x87\xBA\xF0\x9F\x87\xB8";
                break;
            case 'CHN':
                $flag = "\xF0\x9F\x87\xA8\xF0\x9F\x87\xB3";
                break;
            case 'JPN':
                $flag = "\xF0\x9F\x87\xAF\xF0\x9F\x87\xB5";
                break;
            case 'KOR':
                $flag = "\xF0\x9F\x87\xB0\xF0\x9F\x87\xB7";
                break;
            default:
                $flag = " ";
                break;
        }

        return $flag;
    }
}
