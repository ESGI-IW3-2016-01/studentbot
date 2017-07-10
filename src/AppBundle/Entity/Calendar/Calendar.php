<?php

namespace AppBundle\Entity\Calendar;

use AppBundle\Entity\School\StudentGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DateTime, DateTimeZone;
use File;

/**
 * Calendrier / Planning
 *
 * @ORM\Table(name="calendar")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Calendar\CalendarRepository")
 */
class Calendar
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime $updatedAt
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var DateTime $updatedAt
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $processedAt;

    /**
     * @var ArrayCollection $events
     *
     * @ORM\OneToMany(targetEntity="Event", mappedBy="calendar", cascade={"remove"})
     */
    private $events;

    /**
     * @var string $filename
     * @ORM\Column(type="string", nullable=true)
     */
    private $filename;

    /**
     * @var string $filePath
     * @ORM\Column(type="string", nullable=true)
     */
    private $filePath;

    /**
     * @var string $location
     * @ORM\Column(type="string", nullable=true)
     */
    private $location;
    /**
     * Set to true when file has been processed
     * @var bool $processed
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $processed;

    /**
     * @var File
     */
    private $file;

    /**
     * @var StudentGroup $group
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\School\StudentGroup")
     * @ORM\JoinColumn(name="student_group_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $group;

    /**
     * Calendar constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime('now', New DateTimeZone('Europe/Paris'));
        $this->updatedAt = $this->createdAt;
        $this->events = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->updatedAt = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $this->name = $name;
    }

    /**
     * @return ArrayCollection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param ArrayCollection $events
     */
    public function setEvents(ArrayCollection $events)
    {
        $this->updatedAt = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $this->events = $events;
    }

    /**
     * @param Event $event
     * @return bool
     */
    public function addEvent(Event $event): bool
    {
        $this->updatedAt = new DateTime('now', new DateTimeZone('Europe/Paris'));
        return $this->events->add($event);
    }

    /**
     * @param Event $event
     * @return bool
     */
    public function removeEvent(Event $event): bool
    {
        return $this->events->removeElement($event);
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename)
    {
        $this->updatedAt = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $this->filename = $filename;
    }

    /**
     * @return StudentGroup|null
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param StudentGroup $group
     */
    public function setGroup(StudentGroup $group)
    {
        $this->updatedAt = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $this->group = $group;
    }

    /**
     * @return string
     */
    function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return $this->processed;
    }

    /**
     * @param bool $processed
     */
    public function setProcessed(bool $processed)
    {
        $this->processed = $processed;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return DateTime
     */
    public function getProcessedAt(): DateTime
    {
        return $this->processedAt;
    }

    /**
     * @param DateTime $processedAt
     */
    public function setProcessedAt(DateTime $processedAt)
    {
        $this->processedAt = $processedAt;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location)
    {
        $this->location = $location;
    }

}

