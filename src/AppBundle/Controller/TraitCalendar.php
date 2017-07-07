<?php
namespace AppBundle\Controller;

trait TraitCalendar
{
    private function calendar($chaine = null)
    {
        if ($chaine != null) {

            /** @var Calendar $calendar */
            $calendar = $this->container->get('app.calendar_api_service');

            if ($chaine->contains('next class')){
                $res = $calendar->getNextClass();
            } elseif ($chaine->contains('day class')) {
                $res = $calendar->getDayClass();
            } elseif ($chaine->contains('')) {
                
            } else {
                $str = 'Désolé je n\'ai pas compris votre demande. 
                        Ecrivez "planning" pour plus d\'aide ';
                $res = $str;
            }

        } else {
            $str = 'Utilisation de l\'agenda:
                    Ajouté un planning : importer directement un fichier sous format ical.
                    Renseigner son école : écrivez "make a choice"
                    Renseigner sa classe : 
                    
                    Pour connaitre son planning :
                     - le prochain cours : 
                     - les cours de la journée :
                     - les cours de la semaine :
                    ';
            $res = $str;
        }

        return $res;
    }
}
