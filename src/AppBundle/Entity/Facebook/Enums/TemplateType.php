<?php
/**
 *
 */

namespace AppBundle\Entity\Facebook\Enum;


class TemplateType
{

    const TEMPLATE_LIST = 'list';
    const TEMPLATE_BUTTON = 'button';
    const TEMPLATE_GENERIC = 'generic';

    const TYPES = [
        self::TEMPLATE_LIST,
        self::TEMPLATE_BUTTON,
        self::TEMPLATE_GENERIC
    ];
}