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
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.calendar = :calendarId')
            ->andWhere('e.startAt > :startAt')
            ->setParameter('calendarId', $calendarId)
            ->setParameter('startAt', new \DateTime('-5 second'));

        return $qb->getQuery()->getResult();
    }

    public function findDayClass($calendarId)
    {
        $today_startdatetime = \DateTime::createFromFormat( "Y-m-d H:i:s", date("Y-m-d 00:00:01") );
        $today_enddatetime = \DateTime::createFromFormat( "Y-m-d H:i:s", date("Y-m-d 23:59:59") );

        $qb = $this->createQueryBuilder('e');
        $qb->where('e.calendar = :calendarId')
            ->andWhere('e.startAt > :startAt')
            ->andWhere('e.endAt < :endAt')
            ->setParameter('calendarId', $calendarId)
            ->setParameter('startAt', $today_startdatetime)
            ->setParameter('endAt', $today_enddatetime);

        return $qb->getQuery()->getResult();
    }

    public function findWeekClass($calendarId)
    {
        $today_startdatetime = \DateTime::createFromFormat( "Y-m-d H:i:s", date("Y-m-d 00:00:01") );
        $today_enddatetime = strtotime('next sunday');

        $qb = $this->createQueryBuilder('e');
        $qb->where('e.calendar = :calendarId')
            ->andWhere('e.startAt > :startAt')
            ->andWhere('e.endAt < :endAt')
            ->setParameter('calendarId', $calendarId)
            ->setParameter('startAt', $today_startdatetime)
            ->setParameter('endAt', date("Y-m-d H:i:s", $today_enddatetime));

        return $qb->getQuery()->getResult();
    }
}