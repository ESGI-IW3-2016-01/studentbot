<?php

namespace AppBundle\Entity\Facebook\Enum;

final class senderAction
{
    const MARK_SEEN = 0;
    const TYPING_ON = 1;
    const TYPING_OFF = 2;

    public static $codes = [
        self::MARK_SEEN => 'mark_seen',
        self::TYPING_ON => 'typing_on',
        self::TYPING_OFF => 'typing_off'
    ];

    public function __construct($code = self::MARK_SEEN)
    {
        Assertion::keyExists(
            self::$CODES,
            (int) $code,
            sprintf('The action key %s doesn\'t exist in %s class', $code, get_class($this))
        );

        $this->code = (int) $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getCodeLabel()
    {
        return self::$CODES[$this->code ?: self::MARK_SEEN];
    }

    public static function getCodes()
    {
        return array_keys(self::$CODES);
    }
}