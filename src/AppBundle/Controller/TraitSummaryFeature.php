<?php
namespace AppBundle\Controller;

use AppBundle\Entity\QuestionAnswer;
use AppBundle\Service\QuestionAnswerService;

trait TraitSummaryFeature
{
    private function summaryFeature()
    {

        $res = [];
        $res[] = "Voici toutes les fonctionnalités du Robot étudiant : \x0D\x0A";
        $res[] = "planning ou agenda ou calendrier : Agenda de votre école / classe \x0D\x0A";
        $res[] = "\xe2\x9a\xbd : Résultat football \x0D\x0A";
        $res[] = "\xf0\x9f\x8f\x80 : Résultat NBA \x0D\x0A";
        $res[] = "\xF0\x9F\x8E\xAE csgo : Résultat Jeu CSGO \x0D\x0A";
        $res[] = "\xF0\x9F\x8E\xAE dota2 : Résultat Jeu Dota 2 \x0D\x0A";
        $res[] = "\xF0\x9F\x8E\xAE lol : Résultat Jeu LOL \x0D\x0A";
        $res[] = "\xE2\x98\x80 {Ville} : Météo du jour dans votre ville \x0D\x0A";
        $res[] = "\xf0\x9f\x8e\xbc {video youtube} : Vidéo Youtube  \x0D\x0A";
        $res[] = "yes or no ? : GIF FUN \x0D\x0A";
        $res[] = "\xF0\x9F\x93\xB0 : Actualité du moment (en anglais) \x0D\x0A";;

        /** @var QuestionAnswerService $questionAnswerService */
        $questionAnswerService = $this->container->get('app.question_answer_service');
        /** @var QuestionAnswer[] $questionAnswerService */
        $listQuestionAnswer = $questionAnswerService->getAll();

        if($listQuestionAnswer) {
            $res[] = "Fonctionnalités ajoutées par l'administrateur : \x0D\x0A";
            foreach ($listQuestionAnswer as $questionAnswer) {
//                if (count($res) != 1) {
//                    $res[] = "\x0D\x0A ";
//                }
                $res[] = $questionAnswer->getQuestion();
            }
        }

        return $res;
    }
}