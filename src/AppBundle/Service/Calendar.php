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

        $user = $userRepository->findOneBy(['facebookId' => $current_user]);
        $student_group = $studentGroupRepository->find($user->getGroupId());
        $calendar = $calendarRepository->find($student_group->getCalendar());

        $next_class = $eventRepository->findNextClass($calendar->getId());

        return $next_class;
        
    }

    public function getDayClass($current_user)
    {
        $this->logger->info('Cherche cours quotidien');

        $studentGroupRepository = $this->em->getRepository('AppBundle\Entity\School\StudentGroup');
        $userRepository = $this->em->getRepository('AppBundle\Entity\User');
        $calendarRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Calendar');
        $eventRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Event');

        $user = $userRepository->findOneBy(['facebookId' => $current_user]);
        $student_group = $studentGroupRepository->find($user->getGroupId());
        $calendar = $calendarRepository->find($student_group->getCalendar());

        $day_class = $eventRepository->findDayClass($calendar->getId());

        return $day_class;
    }

    public function getWeekClass($current_user)
    {
        $this->logger->info('Cherche cours de la semaine');

        $studentGroupRepository = $this->em->getRepository('AppBundle\Entity\School\StudentGroup');
        $userRepository = $this->em->getRepository('AppBundle\Entity\User');
        $calendarRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Calendar');
        $eventRepository = $this->em->getRepository('AppBundle\Entity\Calendar\Event');

        $user = $userRepository->findOneBy(['facebookId' => $current_user]);
        $student_group = $studentGroupRepository->find($user->getGroupId());
        $calendar = $calendarRepository->find($student_group->getCalendar());

        $week_class = $eventRepository->findWeekClass($calendar->getId());

        return $week_class;
    }
}