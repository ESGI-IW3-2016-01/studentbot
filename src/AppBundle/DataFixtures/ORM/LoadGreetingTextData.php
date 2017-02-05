<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Facebook\GreetingText;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadGreetingTextData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $greeting = new GreetingText("Prêt à commencer ?", "fr_FR");
        $manager->persist($greeting);
        $greeting = new GreetingText("L'assistant personnel des étudiants", "fr_FR");
        $manager->persist($greeting);
        $greeting = new GreetingText("Student's personnal assistant ", "en_UK", true);
        $manager->persist($greeting);

        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }
}