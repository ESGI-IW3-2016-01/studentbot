<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Api;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class LoadApiData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function load(ObjectManager $manager)
    {
        $footballKey = $this->container->getParameter('football_key');
        $basketballKey = $this->container->getParameter('basket_key');
        $weatherKey = $this->container->getParameter('weather_key');

        $api = new Api();
        $api->setName("FOOTBALL");
        $api->setEnabled(true);
        $api->setDescription("Résultats des matchs de foot");
        $api->setToken($footballKey);
        $api->setBaseUrl('https://api.sportradar.us/soccer-t3/eu/fr/');
        $manager->persist($api);

        $api = new Api();
        $api->setName("BASKETBALL");
        $api->setEnabled(true);
        $api->setDescription("Résultats des matchs de basketball");
        $api->setToken($basketballKey);
        $api->setBaseUrl('http://api.sportradar.us/nba-t3/');
        $manager->persist($api);

        $api = new Api();
        $api->setName("WEATHER");
        $api->setEnabled(true);
        $api->setDescription("Météo");
        $api->setToken($weatherKey);
        $api->setBaseUrl('http://api.openweathermap.org/data/2.5/weather');
        $manager->persist($api);
        
        $api = new Api();
        $api->setName("YESORNO");
        $api->setEnabled(true);
        $api->setDescription("Gif yes or no");
        $api->setToken("");
        $api->setBaseUrl('http://yesno.wtf/api/');
        $manager->persist($api);

        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }
}