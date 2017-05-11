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
     * @ORM\ManyToOne(targetEntity="School")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id")
     */
    private $school;


    /**
     * @ORM\OneToOne(targetEntity="Planning", cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumn(name="planning_id", referencedColumnName="id")
     */
    private $planning;


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
     * Set planning
     *
     * @param Planning $planning
     *
     * @return StudentGroup
     */
    public function setPlanning(Planning $planning)
    {
        $this->planning = $planning;

        return $this;
    }

    /**
     * Get planning
     *
     * @return Planning
     */
    public function getPlanning()
    {
        return $this->planning;
    }

}

