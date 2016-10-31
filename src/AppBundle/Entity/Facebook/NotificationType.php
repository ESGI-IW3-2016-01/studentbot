<?php

/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 30/10/2016
 * Time: 10:58
 */
abstract class NotificationType
{
    const regular = "REGULAR";
    const silentPush = "SILENT_PUSH";
    const noPush = "NO_PUSH";

    const codes = [
        self::markSeen,
        self::typingOn,
        self::typingOff
    ];

}