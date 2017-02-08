<?php

namespace AppBundle\Service\EventListener;

/**
 * Created by PhpStorm.
 * User: antoine
 * Date: 08/02/2017
 * Time: 18:22
 */
class ApiEventListener
{
    public function onApiCalled(ApiEvent $apiEvent) {
        dump("HELLOO");
        return;
    }

}