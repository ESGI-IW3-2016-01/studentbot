<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Api;
use AppBundle\Entity\QuestionAnswer;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class LoadQuestionAnswerData implements FixtureInterface, ContainerAwareInterface
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
        $questionAnswer = new QuestionAnswer();
        $questionAnswer->setQuestion("ESGI");
        $questionAnswer->setAnswer("ESGI, grande école d'informatique en alternance à Paris propose 9 filières avec diplômes reconnus par l'État en Cycle Bachelor Niveau 2 et en Cycle Mastère Niveau 1.");
        $manager->persist($questionAnswer);

        $questionAnswer = new QuestionAnswer();
        $questionAnswer->setQuestion("Quelle est la réponse à tout ?");
        $questionAnswer->setAnswer("42 !");
        $manager->persist($questionAnswer);

        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }
}