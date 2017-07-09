<?php

namespace AppBundle\Entity;

use AppBundle\Entity\School\StudentGroup;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\AttributeOverride;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 *
 * @AttributeOverrides({
 *     @AttributeOverride(name="emailCanonical",
 *         column=@ORM\Column(
 *             name="emailCanonical",
 *             type="string",
 *             length=255,
 *             unique=false,
 *             nullable=true
 *         )
 *     ),
 *     @AttributeOverride(name="email",
 *         column=@ORM\Column(
 *             name="email",
 *             type="string",
 *             length=255,
 *             unique=false,
 *             nullable=true
 *         )
 *     )
 * })
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", nullable=true)
     */
    protected $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="sender_id", type="string", nullable=true)
     */
    protected $senderId;

    /**
     * @var StudentGroup
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\School\StudentGroup", inversedBy="user")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_id", type="string", length=255, nullable=true)
     */
    private $photoId;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_extension", type="string", length=255, nullable=true)
     */
    private $photoExtension;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_original_name", type="string", length=255, nullable=true)
     */
    private $photoOriginalName;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\School\School")
     * @ORM\JoinColumn(name="school", referencedColumnName="id")
     */
    private $school;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\School\StudentGroup")
     * @ORM\JoinColumn(name="student_group", referencedColumnName="id")
     */
    private $studentGroup;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    private $temp;

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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    function __get($name)
    {
        return $this->$name;
    }

    function __set($name, $value)
    {
        $this->$name($value);
    }


    /**
     * Gets the value of facebookId.
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Sets the value of facebookId.
     *
     * @param string $facebookId the facebook id
     *
     * @return self
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return string
     */
    public function getSenderId(): string
    {
        return $this->senderId;
    }

    /**
     * @param string $senderId
     */
    public function setSenderId(string $senderId)
    {
        $this->senderId = $senderId;
    }

    /**
     * Set photoId
     *
     * @param string $photoId
     * @return User
     */
    public function setPhotoId($photoId)
    {
        $this->photoId = $photoId;

        return $this;
    }

    /**
     * Get photoId
     *
     * @return string
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }

    /**
     * Set photoExtension
     *
     * @param string $photoExtension
     * @return User
     */
    public function setPhotoExtension($photoExtension)
    {
        $this->photoExtension = $photoExtension;

        return $this;
    }

    /**
     * Get photoExtension
     *
     * @return string
     */
    public function getPhotoExtension()
    {
        return $this->photoExtension;
    }

    /**
     * Set photoOriginalName
     *
     * @param string $photoOriginalName
     * @return User
     */
    public function setPhotoOriginalName($photoOriginalName)
    {
        $this->photoOriginalName = $photoOriginalName;

        return $this;
    }

    /**
     * Get photoOriginalName
     *
     * @return string
     */
    public function getPhotoOriginalName()
    {
        return $this->photoOriginalName;
    }

    /*** GESTION UPLOADS photo de profile ***/

    public function getAbsolutePath()
    {
        return null === $this->photoId
            ? null
            : $this->getUploadRootDir() . '/' . $this->photoId;
    }

    public function getWebPath()
    {
        if ($this->photoId == null) {
            return 'images/inconnu.jpg';
        }

        return $this->getUploadDir() . '/' . $this->photoId;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/profil';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->photoId = $filename . '.' . $this->getFile()->guessExtension();
            $this->photoExtension = $this->file->guessExtension();
            $this->photoOriginalName = $this->file->getClientOriginalName();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // la propriété « file » peut être vide comme le champ n'est pas requis
        if (null === $this->file) {
            return;
        }

        if (!file_exists($this->getUploadRootDir())) {
            mkdir($this->getUploadRootDir());
        }
        // la méthode « move » prend comme arguments le répertoire cible et
        // le nom de fichier cible où le fichier doit être déplacé
        $this->file->move($this->getUploadRootDir(), $this->photoId);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            if (is_file($this->getUploadRootDir() . '/' . $this->temp)) {
                unlink($this->getUploadRootDir() . '/' . $this->temp);
                // clear the temp image path
                $this->temp = null;
            }
        }

        // « nettoie » la propriété « file » comme vous n'en aurez plus besoin
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($this->getphotoId()) {
            $file = $this->getAbsolutePath();
            if ($file && is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        if ($this->file) {
            // check if we have an old image path
            if (isset($this->photoId)) {
                // store the old name to delete after the update
                $this->temp = $this->photoId;
                $this->photoId = null;
            } else {
                $this->photoId = 'initial';
            }
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function removePhoto()
    {
        $file_path = $this->getUploadRootDir() . '/' . $this->getPhotoId();

        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    /**** FIN GESTION UPLOADS ****/

    /**
     * Gets the value of school.
     *
     * @return mixed
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Sets the value of school.
     *
     * @param mixed $school the school
     *
     * @return self
     */
    public function setSchool($school)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Gets the value of studentGroup.
     *
     * @return mixed
     */
    public function getStudentGroup()
    {
        return $this->studentGroup;
    }

    /**
     * Sets the value of studentGroup.
     *
     * @param mixed $studentGroup the student group
     *
     * @return self
     */
    public function setStudentGroup($studentGroup)
    {
        $this->studentGroup = $studentGroup;

        return $this;
    }

    /**
     * @param $studentGroupId
     */
    public function setGroup($studentGroupId)
    {
        $this->group = $studentGroupId;
    }

    /**
     * @return StudentGroup
     */
    public function getGroup()
    {
        return $this->group;
    }
}
