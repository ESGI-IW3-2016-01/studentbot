<?php

namespace AppBundle\Entity\School;

use AppBundle\Entity\School\StudentGroup;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @var StudentGroup
     *
     * @ORM\OneToMany(targetEntity="StudentGroup", mappedBy="school")
     */
    private $studentGroup;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @var string $description
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

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
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set studentGroup
     *
     * @param StudentGroup $studentGroup
     */
    public function setStudentGroup(StudentGroup $studentGroup)
    {
        $this->studentGroup = $studentGroup;
    }

    /**
     * Get studentGroup
     *
     * @return StudentGroup|null
     */
    public function getStudentGroup()
    {
        return $this->studentGroup;
    }

    /**
     * @return string
     */
    function __toString(): string
    {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->studentGroup = new ArrayCollection();
    }

    /**
     * Set street
     *
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
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
     */
    public function setCity($city)
    {
        $this->city = $city;
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
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
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
     * @param StudentGroup $studentGroup
     */
    public function addStudentGroup(StudentGroup $studentGroup)
    {
        $this->studentGroup[] = $studentGroup;
    }

    /**
     * Remove studentGroup
     *
     * @param StudentGroup $studentGroup
     */
    public function removeStudentGroup(StudentGroup $studentGroup)
    {
        $this->studentGroup->removeElement($studentGroup);
    }
}
