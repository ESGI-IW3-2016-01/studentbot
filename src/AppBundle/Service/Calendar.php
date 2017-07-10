<?php

namespace AppBundle\Service;

use AppBundle\Event\ApiEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;


class Calendar
{

    private $logger;
    private $calendarId;

    public function __construct(LoggerInterface $logger, EntityManager $em)
    {
        $this->logger = $logger;
        $this->em = $em;
    }
    
    public function getNextClass($current_user)
    {
        $this->logger->info('Cherche prochain cours');
        $this->logger->info('##############---------------------------------#####################');
        $this->logger->info($current_user);
        $studentGroupRepository = $this->em->getRepository('AppBundle\Entity\School\StudentGroup');
        $userRepository = $this->em->getRepository('AppBundle\Entity\User');
        $calendarRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Calendar');
        $eventRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Event');

        $user = $userRepository->findOneBy(['senderId' => $current_user]);
        if (is_null($user)) {
            return "Vous n'êtes pas un utilisateur ... =)";
        }
        $student_group = $studentGroupRepository->find($user->getGroup()->getId());
        if (is_null($student_group)){
            return "Vous n'êtes dans aucune classe";
        }
        $calendar = $calendarRepository->find($student_group->getCalendar());
        if (is_null($student_group)){
            return "Votre classe n'a pas d'agenda";
        }
        $next_class = $eventRepository->findNextClass($calendar->getId());

        if (empty($next_class)){
            return "Vous n'avez aucun prochain cours aujourd'hui";
        } else {
            $res =  $this->eventString($next_class);
            return $res;
        }
        
    }

    public function getDayClass($current_user)
    {
        $this->logger->info('Cherche cours quotidien');

        $studentGroupRepository = $this->em->getRepository('AppBundle\Entity\School\StudentGroup');
        $userRepository = $this->em->getRepository('AppBundle\Entity\User');
        $calendarRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Calendar');
        $eventRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Event');

        $user = $userRepository->findOneBy(['senderId' => $current_user]);
        if (is_null($user)) {
            return "Vous n'êtes pas un utilisateur ... =)";
        }
        $student_group = $studentGroupRepository->find($user->getGroup()->getId());
        if (is_null($student_group)){
            return "Vous n'êtes dans aucune classe";
        }
        $calendar = $calendarRepository->find($student_group->getCalendar());
        if (is_null($student_group)){
            return "Votre classe n'a pas d'agenda";
        }
        $day_class = $eventRepository->findDayClass($calendar->getId());


        if (empty($day_class)){
            return "Vous n'avez plus de cours aujourd'hui";
        } else {
            if (is_array($day_class)){
                $res = "";
                foreach ($day_class as $event) {
                    $res .= $this->eventString($event);
                }
            } else {
                $res =  $this->eventString($day_class);
            }
            return $res;
        }
    }

    public function getWeekClass($current_user)
    {
        $this->logger->info('Cherche cours de la semaine');

        $studentGroupRepository = $this->em->getRepository('AppBundle\Entity\School\StudentGroup');
        $userRepository = $this->em->getRepository('AppBundle\Entity\User');
        $calendarRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Calendar');
        $eventRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Event');

        $user = $userRepository->findOneBy(['senderId' => $current_user]);
        if (is_null($user)) {
            return "Vous n'êtes pas un utilisateur ... =)";
        }
        $student_group = $studentGroupRepository->find($user->getGroup()->getId());
        if (is_null($student_group)){
            return "Vous n'êtes dans aucune classe";
        }
        $calendar = $calendarRepository->find($student_group->getCalendar());
        if (is_null($student_group)){
            return "Votre classe n'a pas d'agenda";
        }
        $week_class = $eventRepository->findWeekClass($calendar->getId());


        if (empty($week_class)){
            return "Vous n'avez plus de cours cette semaine";
        } else {
            if (is_array($week_class)){
                $res = "";
                foreach ($week_class as $event) {
                    $res .= $this->eventString($event);
                }
            } else {
                $res =  $this->eventString($week_class);
            }
            return $res;
        }
    }

    private function eventString($event){
        if (is_array($event)) {
            $event = $event[0];
        }

        $res = (string)$event->getSummary()."\x0D\x0A";
        $res .= "Le : ".date_format($event->getStartAt(), 'H:i')."\x0D\x0A";
        $res .= "De : ".date_format($event->getStartAt(), 'H:i').". A : ".date_format($event->getEndAt(), 'H:i')."."."\x0D\x0A";
        $res .= (string)$event->getDescription()."\x0D\x0A";

        return $res;
    }
}