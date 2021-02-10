<?php

namespace Angorb\TwinklyControl\Entity;

class Timer
{
    /**
     * Returns the current time as 'seconds after midnight'
     *
     * @return int
     * */
    public static function now(): int
    {
        return (\time() % 86400);
    }

    /**
     * @param int $time Time of day in 24 hour time (e.g. "3:45 PM" = 1545)
     * @return int
     */
    public static function getTime(int $time): int
    {
        return (\round($time / 100) * 3600) + (($time % 100) * 60);
    }
}