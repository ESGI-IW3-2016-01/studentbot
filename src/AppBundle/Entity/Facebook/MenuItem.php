<?php

namespace AppBundle\Entity\Facebook;

use AppBundle\Entity\Facebook\Enums\MenuItemType;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Class MenuItem
 *
 * @author Antoine Cusset <a.cusset@gmail.com>
 * @link https://github.com/acusset
 * @category
 * @license
 * @package AppBundle\Entity\Facebook
 *
 * @ORM\Table(name="menu_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuItemRepository")
 */
class MenuItem
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
     * @var MenuItemType $type
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Facebook\Enums\MenuItemType")
     * @JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=30)
     */
    private $title;

    /**
     * @var bool $enabled
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var string $payload
     * @ORM\Column(name="payload", type="string", nullable=true)
     */
    private $payload;

    /**
     * @var string $url
     * @ORM\Column(name="url", type="string", nullable=true)
     */
    private $url;

    /**
     * MenuItem constructor.
     * @param string $title
     * @param string $type
     * @internal param bool $enabled
     */
    public function __construct(string $title = null, string $type = null)
    {
        $this->type = $type;
        $this->title = $title;
        $this->enabled = false;
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
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param string $payload
     */
    public function setPayload(string $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        switch ($this->type) {
            case MenuItemType::MENU_ITEM_URL:
                $array = [
                    'title' => $this->title,
                    'type' => $this->type->getType(),
                    'url' => $this->url,
                    'webview_height_ratio' => 'full'
                ];
                break;
            case MenuItemType::MENU_ITEM_POSTBACK:
                $array = [
                    'title' => $this->title,
                    'type' => $this->type->getType(),
                    'payload' => $this->payload
                ];
                break;
        }
        return $array;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title . ':' . $this->type->getType();
    }
}