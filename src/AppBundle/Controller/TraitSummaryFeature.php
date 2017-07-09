<?php
namespace AppBundle\Controller;

trait TraitSummaryFeature
{
    private function summaryFeature()
    {

        $res = [];
        $res[] = "Voici toutes les fonctionnalités du Robot étudiant : \x0D\x0A"
                . "planning ou agenda ou calendar : Agenda de votre école / classe \x0D\x0A"
                . "\xe2\x9a\xbd : Résultat football \x0D\x0A"
                . "\xf0\x9f\x8f\x80 : Résultat NBA \x0D\x0A"
                . "\xF0\x9F\x8E\xAE csgo : Résultat Jeu CSGO \x0D\x0A"
                . "\xF0\x9F\x8E\xAE dota2 : Résultat Jeu Dota 2 \x0D\x0A"
                . "\xF0\x9F\x8E\xAE lol : Résultat Jeu LOL \x0D\x0A"
                . "\xE2\x98\x80 {Ville} : Météo du jour dans votre ville \x0D\x0A"
                . "\xf0\x9f\x8e\xbc {video youtube} : Vidéo Youtube  \x0D\x0A"
                . "yes or no ? : GIF FUN \x0D\x0A"
                . "\xF0\x9F\x93\xB0 : Actualité du moment (en anglais) \x0D\x0A";


        $questionAnswerService = $this->container->get('app.question_answer_service');

        $listQuestionAnswer = $questionAnswerService->getAll();

        if($listQuestionAnswer) {
            $str = '';
            foreach ($listQuestionAnswer as $questionAnswer) {
                if (count($res) != 1) {
                    $str .= "\x0D\x0A ";
                }

                $str .= $questionAnswer->getQuestion();
            }

            $res[] = "Fonctionnalités ajoutées par l'administrateur : \x0D\x0A"
                . $str;
        }

        return $res;
    }
}