<?php

namespace Angorb\TwinklyControl\Tests;

use Angorb\TwinklyControl\Timer;
use PHPUnit\Framework\TestCase;

class TimerTest extends TestCase
{
    public function testGetTime(): void
    {
        self::assertEquals(60, Timer::getTime(1)); // 12:01 AM
        self::assertEquals(30060, Timer::getTime(821)); // 8:21am
        self::assertEquals(39060, Timer::getTime(1051)); // 10:51am
        self::assertEquals(43200, Timer::getTime(1200)); // HIGH NOON
        self::assertEquals(56700, Timer::getTime(1545)); // 3:45pm
        self::assertEquals(86340, Timer::getTime(2359)); // 11:59pm
    }
}
