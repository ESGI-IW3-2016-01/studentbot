<?php

/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 30/10/2016
 * Time: 10:31
 */
abstract class senderAction
{
    const markSeen = "mark_seen";
    const typingOn = "typing_on";
    const typingOff = "typing_off";

    const codes = [
        self::markSeen,
        self::typingOn,
        self::typingOff
    ];

}