<?php

namespace Angorb\TwinklyControl;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;

class Timer
{
    /**
     * Returns the current time as 'seconds after midnight'
     *
     * @return int
     * */
    public static function now(?string $timezone = null): int
    {
        if (\false === \is_null($timezone)) {
            $timezone = new DateTimeZone($timezone);
        }

        $date = new DateTime(\null, $timezone);
        $timestamp = $date->getTimestamp() + $date->getOffset();
        return ($timestamp % 86400);
    }

    /**
     * @param int $time Time of day in 24 hour time (e.g. "3:45 PM" = 1545)
     * @return int
     */
    public static function getTime(int $time): int
    {
        return (\floor($time / 100) * 3600) + (($time % 100) * 60);
    }

    public static function ensureValid(int $time)
    {
        if ($time < 0) {
            return 0;
        }

        if ($time > 2359) {
            return 2359;
        }

        if ((int) \substr($time, -2) > 59) {
            throw new InvalidArgumentException(); // HACK
        }
    }
}
