<?php
namespace AppBundle\Controller;

trait TraitQuestionAnswer
{
    private function questionAnswer($question)
    {

        $questionAnswerService = $this->container->get('app.question_answer_service');

        $questionAnswer = $questionAnswerService->getAnswerByQuestion($question);

        if (!$questionAnswer) {
            return false;
        }

        return $questionAnswer->getAnswer();
    }
}
