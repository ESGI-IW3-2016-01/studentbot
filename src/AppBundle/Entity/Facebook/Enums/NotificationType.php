<?php


/**
 * Class NotificationType
 *
 * @author Antoine Cusset <a.cusset@gmail.com>
 * @link https://github.com/acusset
 * @category
 * @license
 */
abstract class NotificationType
{
    const NOTIFICATION_REGULAR = 'REGULAR';
    const NOTIFICATION_SILENT_PUSH = 'SILENT_PUSH';
    const NOTIFICATION_NO_PUSH = 'NO_PUSH';

    const TYPES = [
        self::NOTIFICATION_REGULAR,
        self::NOTIFICATION_SILENT_PUSH,
        self::NOTIFICATION_NO_PUSH,
    ];
}