<?php

namespace AppBundle\Service;

use AppBundle\Entity\Calendar\Event;
use AppBundle\Entity\School\StudentGroup;
use AppBundle\Entity\User;
use AppBundle\Repository\Calendar\EventRepository;
use AppBundle\Repository\StudentGroupRepository;
use AppBundle\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\EntityManager;


class Calendar
{
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * @var EventRepository $eventRepository
     */
    private $eventRepository;

    /**
     * Calendar constructor.
     * @param UserRepository $userRepository
     * @param EventRepository $eventRepository
     */
    public function __construct(UserRepository $userRepository, EventRepository $eventRepository)
    {
        $this->userRepository = $userRepository;
        $this->eventRepository = $eventRepository;
    }

    public function getNextClass($current_user)
    {
        return $this->getEvents('findNextClass', $current_user);
    }

    public function getDayClass($current_user)
    {
        return $this->getEvents('findDayClass', $current_user);
    }

    public function getTomorrowClass($current_user)
    {
        return $this->getEvents('findTomorrowClass', $current_user);
    }

    public function getWeekClass($current_user)
    {
        return $this->getEvents('findWeekClass', $current_user);
    }


    public function getEvents($method, $userId)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['senderId' => $userId]);
        if (is_null($user)) {
            return 'Vous n\'êtes pas un utilisateur ... =)';
        }

        /** @var StudentGroup $student_group */
        $student_group = $user->getStudentGroup();
        if (is_null($student_group)) {
            return 'Vous n\'êtes dans aucune classe';
        }

        /** @var \AppBundle\Entity\Calendar\Calendar $calendar */
        $calendar = $student_group->getCalendar();
        if (is_null($student_group)) {
            return 'Votre classe n\'a pas d\'agenda';
        }

        $events = $this->eventRepository->$method($calendar->getId());

        if (empty($week_class)) {
            return 'Vous n\'avez plus de cours cette semaine';
        } else {
            $res = '';
            /** @var Event $event */
            foreach ($events as $event) {
                $res .= $this->eventString($event) . "\x0D\x0A";
            }
        }

        return $res;
    }

    /**
     * @param Event $event
     * @return string
     */
    private function eventString(Event $event)
    {
        if (is_array($event)) {
            $event = array_pop($event);
        }

        $res = (string)$event->getSummary() . "\x0D\x0A";
        $res .= 'Le : ' . date_format($event->getStartAt(), 'd/m') . "\x0D\x0A";
        $res .= 'De : ' . date_format($event->getStartAt(), 'H:i') . '. A : ' . date_format($event->getEndAt(), 'H:i') . ".\x0D\x0A";
        $res .= (string)$event->getDescription() . "\x0D\x0A";

        return $res;
    }
}