<?php

namespace AppBundle\Service;

use AppBundle\Entity\Api;
use Doctrine\ORM\EntityManager;

class ApiService
{
    /**
     * @var EntityManager $manager
     */
    private $manager;

    /**
     * ApiService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->manager = $entityManager;
    }

    public function getApi($apiName)
    {
        $apiRepository = $this->manager->getRepository('AppBundle:Api');
        $api = $apiRepository->findOneBy(["name" => $apiName, "enabled" => true]);
        $this->manager->flush();

        return $api;
    }
}