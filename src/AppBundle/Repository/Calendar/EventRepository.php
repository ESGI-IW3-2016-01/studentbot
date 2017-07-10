<?php

namespace AppBundle\Repository\Calendar;

use Doctrine\ORM\EntityRepository;
use DateTime, DateInterval;

class EventRepository extends EntityRepository
{

    public function findNextClass($calendarId)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.calendar = :calendarId')
            ->andWhere('e.startAt > :startAt')
            ->orderBy('e.startAt', 'ASC')
            ->setParameter('calendarId', $calendarId)
            ->setParameter('startAt', new \DateTime('-5 second'));

        return $qb->getQuery()->getResult();
    }

    public function findDayClass($calendarId)
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s',date("Y-m-d H:i:s"));

        $qb = $this->createQueryBuilder('e');
        $qb->where('e.calendar = :calendarId')
            ->andWhere('e.startAt >= :startAt')
            ->andWhere('e.endAt < :endAt')
            ->orderBy('e.startAt', 'ASC')
            ->setParameter('calendarId', $calendarId)
            ->setParameter('startAt', $date->format('Y-m-d'))
            ->setParameter('endAt', $date->add(new DateInterval('P1D'))->format('Y-m-d'));

        return $qb->getQuery()->getResult();
    }

    public function findWeekClass($calendarId)
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s',date("Y-m-d H:i:s"));
        $today_enddatetime = strtotime('next sunday');

        $qb = $this->createQueryBuilder('e');
        $qb->where('e.calendar = :calendarId')
            ->andWhere('e.startAt >= :startAt')
            ->andWhere('e.endAt < :endAt')
            ->orderBy('e.startAt', 'ASC')
            ->setParameter('calendarId', $calendarId)
            ->setParameter('startAt', $date)
            ->setParameter('endAt', date("Y-m-d H:i:s", $today_enddatetime));

        return $qb->getQuery()->getResult();
    }
}