<?php
/**
 *
 */

namespace AppBundle\Entity\Facebook\Enum;


abstract class ButtonType
{
    const BUTTON_URL = 'web_url';
    const BUTTON_POSTBACK = 'postback';
    const BUTTON_PHONE = 'phone_number';
    const BUTTON_SHARE = 'element_share';
    const BUTTON_PAY = 'payment';

    const TYPES = [
        self::BUTTON_URL,
        self::BUTTON_POSTBACK,
        self::BUTTON_PHONE,
        self::BUTTON_SHARE,
        self::BUTTON_PAY
    ];
}