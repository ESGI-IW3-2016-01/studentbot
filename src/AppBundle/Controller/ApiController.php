<?php
/**
 *
 */

namespace AppBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as AdminBaseController;

class ApiController extends AdminBaseController
{

    protected function prePersistEntity($api) {
        if (method_exists($api, 'setName')) {
            $api->setName(strtoupper($api->getName()));
        }
    }

    protected function preUpdateEntity($api)
    {
        if (method_exists($api, 'setName')) {
            $api->setName(strtoupper($api->getName()));
        }
    }
}