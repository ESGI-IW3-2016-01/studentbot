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
            } elseif ($chaine->contains('')) {

            } else {
                $str = 'Sorry i don\'t understand your request. 
                        Please write "planning" for help ';
                $res = $str;
            }

        } else {
            $str = 'Utilisation de l\'agenda:
                    Ajouté un planning : importer directement un fichier sous format ical.
                    ';
            $res = $str;
        }

        return $res;
    }
}
