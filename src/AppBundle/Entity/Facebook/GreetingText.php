<?php

namespace AppBundle\Entity\Facebook;

use AppBundle\Entity\Facebook\Enums\Locales;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Table(name="greeting_text")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GreetingTextRepository")
 */
class GreetingText
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $text;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @var Locales $locale
     * @OneToOne(targetEntity="AppBundle\Entity\Facebook\Enums\Locales")
     * @JoinColumn(name="locales_id", referencedColumnName="id")
     */
    private $locale;

    /**
     * GreetingText constructor.
     * @internal param $text
     * @internal param $locale
     * @internal param $enabled
     */
    public function __construct()
    {
        $this->enabled = false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        if (in_array($locale, Locales::SUPPORTED_LOCALES)) {
            $this->locale = $locale;
        } else {
            $this->locale = new Locales('default');
        }
    }

    public function toArray()
    {
        return [
            'locale' => $this->locale->getValue(),
            'text' => $this->text
        ];
    }
}