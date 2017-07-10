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
            return "Vous n'avez aucun prochain cours";
        }
        if (is_array($next_class)) {
            return $next_class[0];
        }
        return $next_class;
        
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
            return "Vous n'avez aucun prochain cours";
        }
        return $day_class;
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
            return "Vous n'avez aucun prochain cours";
        }
        return $week_class;
    }
}