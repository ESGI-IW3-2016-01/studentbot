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
                $str = 'Désolé je n\'ai pas compris votre demande. \x0D\x0A
                        Ecrivez "planning" pour plus d\'aide ';
                $res = $str;
            }

        } else {
            $str = 'Utilisation de l\'agenda: \x0D\x0A
                    Ajouté un planning : importer directement un fichier sous format ical. \x0D\x0A
                    Renseigner son école : écrivez "Agenda école" \x0D\x0A
                    Renseigner sa classe : écrivez "Agenda classe" \x0D\x0A
                     \x0D\x0A
                    Pour connaitre son agenda : \x0D\x0A
                     - le prochain cours : écrivez "Agenda prochain" \x0D\x0A
                     - les cours de la journée : écrivez "Agenda cours jours" \x0D\x0A
                     - les cours de la semaine : écrivez "Agenda cours semaine" \x0D\x0A
                    ';
            $res = $str;
        }

        return $res;
    }
}
