<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * School
 *
 * @ORM\Table(name="school")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SchoolRepository")
 */
class School
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
     * @ORM\Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(name="postal_code", type="integer")
     */
    private $postalCode;
    
    /**
     * @ORM\OneToMany(targetEntity="StudentGroup", mappedBy="school")
     */
    private $studentGroup;


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
     * @return School
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
     * Set studentGroup
     *
     * @param StudentGroup $studentGroup
     *
     * @return StudentGroup
     */
    public function setStudentGroup(StudentGroup $studentGroup)
    {
        $this->studentGroup = $studentGroup;

        return $this;
    }

    /**
     * Get studentGroup
     *
     * @return StudentGroup
     */
    public function getStudentGroup()
    {
        return $this->studentGroup;
    }

    public function __toString()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->studentGroup = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return School
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return School
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postalCode
     *
     * @param integer $postalCode
     *
     * @return School
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return integer
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Add studentGroup
     *
     * @param \AppBundle\Entity\StudentGroup $studentGroup
     *
     * @return School
     */
    public function addStudentGroup(\AppBundle\Entity\StudentGroup $studentGroup)
    {
        $this->studentGroup[] = $studentGroup;

        return $this;
    }

    /**
     * Remove studentGroup
     *
     * @param \AppBundle\Entity\StudentGroup $studentGroup
     */
    public function removeStudentGroup(\AppBundle\Entity\StudentGroup $studentGroup)
    {
        $this->studentGroup->removeElement($studentGroup);
    }
}
