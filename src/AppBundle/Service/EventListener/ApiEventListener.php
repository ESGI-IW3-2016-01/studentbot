<?php

namespace AppBundle\Service\EventListener;

use AppBundle\Entity\Api;
use AppBundle\Entity\ApiLog;
use AppBundle\Event\ApiEvent;
use AppBundle\Repository\ApiRepository;
use Doctrine\ORM\EntityManager;

class ApiEventListener
{
    /** @var EntityManager $manager */
    private $manager;

    public function __construct(EntityManager $entityManager)
    {
        $this->manager = $entityManager;
    }

    public function onApiCalled(ApiEvent $apiEvent) {
        /** @var ApiRepository $apiRepository */
        $apiRepository = $this->manager->getRepository('AppBundle:Api');
        /** @var Api $api */
        $api = $apiRepository->findOneBy(["name" => strtoupper($apiEvent->getApiName())]);
        /** @var ApiLog $log */
        $log = new ApiLog($api, intval($apiEvent->getResponseCode()));

        $this->manager->persist($log);
        $this->manager->flush();

        return;
    }
}