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
            $str = 'Utilisation de l\'agenda:
                    Ajouté un planning : importer directement un fichier sous format ical.
                    Renseigner son école : écrivez "Agenda école"
                    Renseigner sa classe : écrivez "Agenda classe"
                    Pour connaitre son agenda :
                     - le prochain cours : écrivez "Agenda prochain"
                     - les cours de la journée : écrivez "Agenda cours jours"
                     - les cours de la semaine : écrivez "Agenda cours semaine"
                    ';
            $res = $str;
        }

        return $res;
    }
}
