<?php

namespace AppBundle\Entity\Facebook;

use Doctrine\ORM\Mapping as ORM;

/**
 * Emoji
 *
 * @ORM\Table(name="emoji")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmojiRepository")
 */
class Emoji
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
     * @ORM\Column(name="unicode", type="string", length=255)
     */
    protected $unicode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="bytes", type="string", length=255)
     */
    protected $bytes;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    protected $description;
    
    /**
     * Get unicode
     * @return string $unicode
     */
    public function getUnicode()
    {
        return $this->unicode;
    }

    /**
     * set unicode
     * @param string $unicode
     */
    public function setUnicode($unicode)
    {
        $this->unicode = $unicode;
    }

    /**
     * Get bytes
     * 
     * @return string $bytes
     */
    public function getBytes()
    {
        return $this->bytes;
    }

    /**
     * Set bytes
     * 
     * @param string $bytes
     */
    public function setBytes($bytes)
    {
        $this->bytes = $bytes;
    }

    /**
     * Get description
     * 
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}