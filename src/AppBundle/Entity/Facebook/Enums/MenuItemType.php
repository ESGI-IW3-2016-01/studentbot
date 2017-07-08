<?php
/**
 * INSERT INTO menu_item_type (type) VALUES ('web_url'), ('postback'), ('nested')
 */

namespace AppBundle\Entity\Facebook\Enums;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MenuItemType
 *
 * @author Antoine Cusset <a.cusset@gmail.com>
 * @link https://github.com/acusset
 * @category
 * @license
 * @package AppBundle\Entity\Facebook\Enum
 *
 * @ORM\Table(name="menu_item_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuItemTypesRepository")
 */
class MenuItemType
{

    const MENU_ITEM_URL = 'web_url';
    const MENU_ITEM_POSTBACK = 'postback';
    const MENU_ITEM_NESTED = 'nested';

    const TYPES = [
        self::MENU_ITEM_URL,
        self::MENU_ITEM_POSTBACK,
        self::MENU_ITEM_NESTED
    ];

    /**
     * @var int $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=10)
     */
    private $type;

    /**
     * MenuItemTypes constructor.
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getId(): int
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        if (!in_array($type, self::TYPES )) {
            throw new \InvalidArgumentException("Invalid menu item type");
        }
        $this->type = $type;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->type;
    }
}