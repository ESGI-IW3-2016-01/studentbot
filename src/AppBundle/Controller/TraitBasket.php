<?php
namespace AppBundle\Controller;

trait TraitBasket
{
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
}