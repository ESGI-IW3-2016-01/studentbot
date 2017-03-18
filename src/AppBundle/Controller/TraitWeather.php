<?php
namespace AppBundle\Controller;

trait TraitWeather
{
    private function weather($city)
    {
        $weather = $this->container->get('app.weather_api_service');
        $json_data = $weather->getWeatherByCity($city);

        $data = json_decode($json_data);

        $res = "Météo " . $data->name . " : " . $data->weather[0]->description . " | Température " . round($data->main->temp) . "°C";

        return $res;
    }
}
