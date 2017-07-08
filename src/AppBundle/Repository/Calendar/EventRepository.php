<?php

namespace AppBundle\Repository\Calendar;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{

    /**
     *
     */
    public function findNextClass($calendarId)
    {
        return $this->getEntityManager()->getRepository('AppBundle\Entity\Calendar\Event')->findOneBy(
            array('calendar' => $calendarId, 'startAt' => new \DateTime('-5 second'))
        );
    }

    public function findDayClass($calendarId)
    {
        $timestamp = strtotime('today midnight');
        return $this->getEntityManager()->getRepository('AppBundle\Entity\Calendar\Event')->findBy(
            array('calendar' => $calendarId, 'startAt' => new \DateTime('-5 second'), 'endAt' <= date("Y-m-d H:i:s", time() + $timestamp))
        );
    }

    public function findWeekClass($calendarId)
    {
        $timestamp = strtotime('today sunday');
        return $this->getEntityManager()->getRepository('AppBundle\Entity\Calendar\Event')->findBy(
            array('calendar' => $calendarId, 'startAt' => new \DateTime('-5 second'), 'endAt' <= date("Y-m-d H:i:s", time() + $timestamp))
        );
    }
}