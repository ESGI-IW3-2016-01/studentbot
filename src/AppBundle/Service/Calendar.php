<?php

namespace AppBundle\Service;

use AppBundle\Entity\Calendar\Event;
use AppBundle\Entity\School\StudentGroup;
use AppBundle\Entity\User;
use AppBundle\Repository\Calendar\CalendarRepository;
use AppBundle\Repository\Calendar\EventRepository;
use AppBundle\Repository\StudentGroupRepository;
use AppBundle\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
        /** @var UserRepository $userRepository */
        $userRepository = $this->em->getRepository('AppBundle\Entity\User');
        /** @var EventRepository $eventRepository */
        $eventRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Event');

        /** @var User $user */
        $user = $userRepository->findOneBy(['senderId' => $current_user]);
        if (is_null($user)) {
            return "Vous n'êtes pas un utilisateur ... =)";
        }

        /** @var StudentGroup $student_group */
        $student_group = $user->getStudentGroup();
        if (is_null($student_group)){
            return "Vous n'êtes dans aucune classe";
        }
        /** @var \AppBundle\Entity\Calendar\Calendar $calendar */
        $calendar = $student_group->getCalendar();
        if (is_null($calendar)){
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
        /** @var UserRepository $userRepository */
        $userRepository = $this->em->getRepository('AppBundle\Entity\User');
        /** @var EventRepository $eventRepository */
        $eventRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Event');

        /** @var User $user */
        $user = $userRepository->findOneBy(['senderId' => $current_user]);
        if (is_null($user)) {
            return "Vous n'êtes pas un utilisateur ... =)";
        }

        /** @var StudentGroup $student_group */
        $student_group = $user->getStudentGroup();
        if (is_null($student_group)){
            return "Vous n'êtes dans aucune classe";
        }

        /** @var \AppBundle\Entity\Calendar\Calendar $calendar */
        $calendar = $student_group->getCalendar();
        if (is_null($student_group)){
            return "Votre classe n'a pas d'agenda";
        }

        $day_class = $eventRepository->findDayClass($calendar->getId());

        if (empty($day_class)){
            return "Vous n'avez plus de cours aujourd'hui";
        } else {
            if (is_array($day_class)){
                $res = "";
                /** @var Event $event */
                foreach ($day_class as $event) {
                    $res .= $this->eventString($event)."\x0D\x0A";
                }
            } else {
                $res =  $this->eventString($day_class);
            }
            return $res;
        }
    }

    public function getTomorrowClass($current_user)
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->em->getRepository('AppBundle\Entity\User');
        /** @var CalendarRepository $eventRepository */
        $eventRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Event');

        /** @var User $user */
        $user = $userRepository->findOneBy(['senderId' => $current_user]);
        if (is_null($user)) {
            return "Vous n'êtes pas un utilisateur ... =)";
        }

        /** @var StudentGroup $student_group */
        $student_group = $user->getStudentGroup();
        if (is_null($student_group)){
            return "Vous n'êtes dans aucune classe";
        }

        /** @var \AppBundle\Entity\Calendar\Calendar $calendar */
        $calendar = $student_group->getCalendar();
        if (is_null($student_group)){
            return "Votre classe n'a pas d'agenda";
        }

        $day_class = $eventRepository->findTomorrowClass($calendar->getId());

        if (empty($day_class)){
            return "Vous n'avez pas de cours demain";
        } else {
            if (is_array($day_class)){
                $res = "";
                /** @var Event $event */
                foreach ($day_class as $event) {
                    $res .= $this->eventString($event)."\x0D\x0A";
                }
            } else {
                $res =  $this->eventString($day_class);
            }
            return $res;
        }
    }

    public function getWeekClass($current_user)
    {
        /** @var StudentGroupRepository $userRepository */
        $userRepository = $this->em->getRepository('AppBundle\Entity\User');
        /** @var EventRepository $eventRepository */
        $eventRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Event');

        /** @var User $user */
        $user = $userRepository->findOneBy(['senderId' => $current_user]);
        if (is_null($user)) {
            return "Vous n'êtes pas un utilisateur ... =)";
        }

        /** @var StudentGroup $student_group */
        $student_group = $user->getStudentGroup();
        if (is_null($student_group)){
            return "Vous n'êtes dans aucune classe";
        }

        /** @var \AppBundle\Entity\Calendar\Calendar $calendar */
        $calendar = $student_group->getCalendar();
        if (is_null($student_group)){
            return "Votre classe n'a pas d'agenda";
        }

        $week_class = $eventRepository->findWeekClass($calendar->getId());

        if (empty($week_class)){
            return "Vous n'avez plus de cours cette semaine";
        } else {
            if (is_array($week_class)){
                $res = "";
                /** @var Event $event */
                foreach ($week_class as $event) {
                    $res .= $this->eventString($event)."\x0D\x0A";
                }
            } else {
                $res =  $this->eventString($week_class);
            }
            return $res;
        }
    }

    /**
     * @param Event $event
     * @return string
     */
    private function eventString(Event $event){
        if (is_array($event)) {
            $event = $event[0];
        }

        $res = (string)$event->getSummary()."\x0D\x0A";
        $res .= "Le : ".date_format($event->getStartAt(), 'd/m')."\x0D\x0A";
        $res .= "De : ".date_format($event->getStartAt(), 'H:i').". A : ".date_format($event->getEndAt(), 'H:i')."."."\x0D\x0A";
        $res .= (string)$event->getDescription()."\x0D\x0A";

        return $res;
    }
}