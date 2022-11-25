<?php

namespace Angorb\TwinklyControl\Tests;

use Angorb\TwinklyControl\TwinklyControl;
use PHPUnit\Framework\TestCase;

class TwinklyControlTest extends TestCase
{
    private TwinklyControl $tc;

    public function setUp(): void
    {
        $this->tc = new TwinklyControl($_ENV['DEVICE_IP']);
        self::assertInstanceOf('Angorb\\TwinklyControl\\TwinklyControl', $this->tc);
    }

    public function tearDown(): void
    {
        unset($this->tc);
    }

    public function testGetBrightness(): void
    {
        $brightnessValue = $this->tc->brightness();
        self::assertIsInt($brightnessValue);
    }

    public function testZeroBrightness(): void
    {
        $this->tc->brightness(0);
        self::assertEquals(0, $this->tc->brightness());
    }

    public function testArbitratyBrightness(): void
    {
        $randomBrightness = random_int(0, 100);
        $this->tc->brightness($randomBrightness);
        self::assertEquals($randomBrightness, $this->tc->brightness());
    }

    public function testFullBrightness(): void
    {
        $this->tc->brightness(100);
        self::assertEquals(100, $this->tc->brightness());
    }
}
