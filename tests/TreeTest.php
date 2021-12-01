<?php

namespace Angorb\TwinklyControl\Tests;

use Angorb\TwinklyControl\Tree;
use PHPUnit\Framework\TestCase;

class TreeTest extends TestCase
{
    private Tree $tree;

    public function setUp(): void
    {
        $this->tree = new Tree($_ENV['TREE_IP']);
        self::assertInstanceOf('Angorb\\TwinklyControl\\Tree', $this->tree);
    }

    public function tearDown(): void
    {
        unset($this->tree);
    }

    public function testGetBrightness(): void
    {
        $brightnessValue = $this->tree->brightness();
        self::assertIsInt($brightnessValue);
    }

    public function testZeroBrightness(): void
    {
        $this->tree->brightness(0);
        self::assertEquals(0, $this->tree->brightness());
    }

    public function testArbitratyBrightness(): void
    {
        $randomBrightness = random_int(0, 100);
        $this->tree->brightness($randomBrightness);
        self::assertEquals($randomBrightness, $this->tree->brightness());
    }

    public function testFullBrightness(): void
    {
        $this->tree->brightness(100);
        self::assertEquals(100, $this->tree->brightness());
    }
}
