<?php
namespace AppBundle\Controller;

trait TraitQuestionAnswer
{
    private function questionAnswer($question)
    {

        $em = $this->getDoctrine()->getManager();

        $questionAnswer = $em->getRepository('AppBundle:QuestionAnswer')
                        ->findBy(
                            array('question' => $question)
                        );

        if (!$questionAnswer) {
            return false;
        }

        return $questionAnswer->answer;
    }
}
