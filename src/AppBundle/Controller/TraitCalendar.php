<?php
namespace AppBundle\Controller;

trait TraitCalendar
{
    private function calendar($chaine = null, $current_user)
    {
        if ($chaine != null) {

            /** @var Calendar $calendar */
            $calendar = $this->container->get('app.calendar_api_service');

            if (strpos($chaine, 'prochain')){
                $res = $calendar->getNextClass($current_user);
            } elseif (strpos($chaine, 'jours')) {
                $res = $calendar->getDayClass($current_user);
            } elseif (strpos($chaine, 'demain')) {
                $res = $calendar->getTomorrowClass($current_user);
            } elseif (strpos($chaine, 'semaine')) {
                $res = $calendar->getWeekClass($current_user);
            } elseif (strpos($chaine, 'école')) {
                $res = 'school';
            } elseif (strpos($chaine, 'classe')) {
                $res = 'class';
            } else {
                $str = 'Désolé je n\'ai pas compris votre demande.
                        Ecrivez "planning" pour plus d\'aide ';
                $res = $str;
            }

        } else {

            $res = "";
            $res .= "  Utilisation de l'agenda : \x0D\x0A";
            $res .= "Ajouté un planning : importer directement un fichier sous format ical. \x0D\x0A";
            $res .= "Renseigner son école => \"Agenda école\" \x0D\x0A";
            $res .= "Renseigner sa classe   => \"Agenda classe\" \x0D\x0A";
            $res .= "Pour connaitre son agenda : \x0D\x0A";
            $res .= "- prochain cours          => \"Agenda prochain\" \x0D\x0A";
            $res .= "- cours de la journée   => \"Agenda jours\" \x0D\x0A";
            $res .= "- cours de demain       => \"Agenda demain\" \x0D\x0A";
            $res .= "- cours de la semaine  => \"Agenda semaine\" \x0D\x0A";
        }

        return $res;
    }
}
