<?php
/**
 *
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Facebook\Enums\MenuItemType;
use AppBundle\Entity\Facebook\MenuItem;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as AdminBaseController;

class MenuItemController extends AdminBaseController
{
    /**
     * Before NEW
     * @param $menuItem
     */
    protected function prePersistEntity($menuItem)
    {
        $menuItem->setTitle(substr($menuItem->getTitle(), 0, 30));

        if ($menuItem->getType()->getType() === MenuItemType::MENU_ITEM_POSTBACK &&
            method_exists($menuItem, 'setPayload')
        ) {
            $menuItem->setPayload(
                'MENU_ITEM_' . strtoupper($this->slugify($menuItem->getTitle())) . '_PAYLOAD'
            );
            $menuItem->setUrl(null);
        } elseif ($menuItem->getType() === MenuItemType::MENU_ITEM_URL) {
            if (empty($menuItem->getUrl())) {
                // TODO Exception !
            }
        }
    }

    /**
     * Before EDIT
     * @param object $menuItem
     */
    protected function preUpdateEntity($menuItem)
    {
        $menuItem->setTitle(substr($menuItem->getTitle(), 0, 30));

        if ($menuItem->getType()->getType() == MenuItemType::MENU_ITEM_POSTBACK &&
            method_exists($menuItem, 'setPayload')
        ) {
            $menuItem->setUrl(null);
            $menuItem->setPayload(
                'MENU_ITEM_' . strtoupper($this->slugify($menuItem->getTitle())) . '_PAYLOAD'
            );
        }
    }

    /**
     * Slugify
     * @param $text
     * @return string
     */
    private function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '_', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '_', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}