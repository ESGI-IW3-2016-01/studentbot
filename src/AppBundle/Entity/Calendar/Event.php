<?php

namespace AppBundle\Entity\Calendar;

use DateTime;
use DateInterval;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Event
 * @package AppBundle\Entity\Calendar
 *
 * @ORM\Table(name="events")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Calendar\EventRepository")
 */
class Event
{
    // TODO : trim and replace special chars in strings
    // TODO : parse description, summary to extract location, teachers and info

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    private $uid;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $startAt;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $endAt;

    /**
     * @var string
     * @ORM\Column(type="string", length=1023)
     */
    private $summary;

    /**
     * @var string
     * @ORM\Column(type="string", length=1023)
     */
    private $description;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @var Calendar
     *
     * @ORM\ManyToOne(targetEntity="Calendar", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $calendar;

    /**
     * Event constructor.
     * @param $uid
     * @param $description
     * @param $summary
     * @param $createdAt
     * @param $updatedAt
     * @param $startAt
     * @param $endAt
     * @param $timestamp
     */
    public function __construct($uid, $description, $summary, $createdAt, $updatedAt, $startAt, $endAt, $timestamp)
    {
        $this->setUid($uid);
        $this->setDescription($description ?? '');
        $this->setSummary($summary);
        $this->setCreatedAt($createdAt);
        $this->setUpdatedAt($updatedAt);
        $this->setStartAt($startAt);
        $this->setEndAt($endAt);
        $this->duration = $this->endAt->diff($this->startAt);
        $this->setTimestamp($timestamp);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     */
    public function setUid(string $uid)
    {
        $this->uid = $this->_parseUid($uid);
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedAt
     */
    public function setUpdatedAt(string $updatedAt)
    {
        $this->updatedAt = new DateTime($updatedAt);
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = new DateTime($createdAt);
    }

    /**
     * @return DateTime
     */
    public function getStartAt(): DateTime
    {
        return $this->startAt;
    }

    /**
     * @param DateTime $startAt
     */
    public function setStartAt(DateTime $startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return DateTime
     */
    public function getEndAt(): DateTime
    {
        return $this->endAt;
    }

    /**
     * @param DateTime $endAt
     */
    public function setEndAt(DateTime $endAt)
    {
        $this->endAt = $endAt;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary)
    {
        $this->summary = $this->_formatString($summary);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $this->_parseDescription($description);
    }

    /**
     * @return DateTime
     */
    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param DateTime $timestamp
     */
    public function setTimestamp(DateTime $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Used to display human readable times in easy admin
     *
     * @return string
     */
    public function getStringDuration(): string
    {
        if ((int)$this->endAt->diff($this->startAt)->format('%d') >= 1) {
            $format = '%dj';
        } else {
            $format = '%hh%I';
        }
        return $this->endAt->diff($this->startAt)->format($format);
    }

    /**
     * @return DateInterval
     */
    public function getDuration(): DateInterval
    {
        return $this->endAt->diff($this->startAt);
    }

    /**
     * @return Calendar
     */
    public function getCalendar(): Calendar
    {
        return $this->calendar;
    }

    /**
     * @param Calendar $calendar
     */
    public function setCalendar(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }


    /**
     * Trim and remove formatting in strings imported from calendar
     * @param $string
     * @return string
     */
    private function _formatString($string)
    {
        $string = str_replace('&amp;', '&', $string);
        $string = str_replace(PHP_EOL, '', $string);
        return trim($string);
    }

    /**
     * Extract data from uid
     * Example raw uids "Férié-594-5A - IW 3-Index-Education", "Cours-508333-33-5A - IW 3-Index-Education"
     * @param string $string
     * @return string
     */
    private function _parseUid(string $string): string
    {
        return $string;
    }

    /**
     * Exemple : Matière : Anglais Préparation au Toeic 2\n
     * Enseignants : M. BRENEUR, M. GARDINIER, M. HIDALGO-BARQUERO, M. NOEL, M. RAULIN, M. WEBER\n
     * Promotions : 5A - IW 3, 5A - MCSI 1, 5A - MCSI 2, 5A - SRC 3\n
     * Salles : NA SALLE A 14, NA SALLE A 23, NA SALLE A 24, NA SALLE C 02, NA SALLE C 03, NA SALLE C 05 - Salle de Dessin
     * @param string $string
     * @return string
     */
    private function _parseDescription(string $string): string
    {
        return $string;
    }

    /**
     * @param string $string
     * @return string
     */
    private function _parseSummary(string $string): string
    {
        return $string;
    }

    function __toString()
    {
        return $this->summary;
    }

}