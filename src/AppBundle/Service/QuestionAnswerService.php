<?php

namespace AppBundle\Service;

use AppBundle\Entity\QuestionAnswer;
use Doctrine\ORM\EntityManager;

class QuestionAnswerService
{
    /** @var EntityManager $manager */
    private $manager;

    public function __construct(EntityManager $entityManager)
    {
        $this->manager = $entityManager;
    }

    public function getAnswerByQuestion($question) {
        $questionAnswerRepository = $this->manager->getRepository('AppBundle:QuestionAnswer');
        $questionAnswer = $questionAnswerRepository->findOneBy(["question" => $question]);

        $this->manager->flush();

        return $questionAnswer;
    }
}