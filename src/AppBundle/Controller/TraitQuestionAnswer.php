<?php
namespace AppBundle\Controller;

trait TraitQuestionAnswer
{
    private function questionAnswer($question)
    {
        $res = [];
        $questionAnswerService = $this->container->get('app.question_answer_service');

        $questionAnswer = $questionAnswerService->getAnswerByQuestion($question);

        if (!$questionAnswer) {
            return false;
        }

        $lines = explode('*',$questionAnswer->getAnswer());

        foreach ($lines as $line) {
            $res[] = $line;
        }

        return $res;
    }
}
