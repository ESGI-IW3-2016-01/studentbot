<?php

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