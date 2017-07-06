<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudentGroup
 *
 * @ORM\Table(name="student_group")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentGroupRepository")
 */
class StudentGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="schoolYear", type="integer")
     */
    private $schoolYear;

    /**
     * @ORM\ManyToOne(targetEntity="Promotion")
     * @ORM\JoinColumn(name="promotion_id", referencedColumnName="id")
     */
    private $promotion;

    /**
     * @ORM\ManyToOne(targetEntity="School", inversedBy="studentGroup")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id")
     */
    private $school;


    /**
     * @ORM\OneToOne(targetEntity="Agenda", cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumn(name="agenda_id", referencedColumnName="id")
     */
    private $agenda;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return StudentGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set schoolYear
     *
     * @param integer $schoolYear
     *
     * @return StudentGroup
     */
    public function setSchoolYear($schoolYear)
    {
        $this->schoolYear = $schoolYear;

        return $this;
    }

    /**
     * Get schoolYear
     *
     * @return int
     */
    public function getSchoolYear()
    {
        return $this->schoolYear;
    }


    /**
     * Set school
     *
     * @param School $school
     *
     * @return StudentGroup
     */
    public function setSchool(School $school)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return School
     */
    public function getSchool()
    {
        return $this->school;
    }


    /**
     * Set agenda
     *
     * @param Agenda $agenda
     *
     * @return StudentGroup
     */
    public function setAgenda(Agenda $agenda)
    {
        $this->agenda = $agenda;

        return $this;
    }

    /**
     * Get agenda
     *
     * @return Agenda
     */
    public function getAgenda()
    {
        return $this->agenda;
    }


    /**
     * Gets the value of promotion.
     *
     * @return int
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Sets the value of promotion.
     *
     * @param int $promotion the promotion
     *
     * @return self
     */
    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getFullName() {
        return $this->schoolYear. " ". $this->name;
    }

    public function __toString() {
        return $this->getFullName();
    }
}

