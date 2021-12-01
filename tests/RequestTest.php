<?php

namespace Angorb\TwinklyControl\Tests;

use Angorb\TwinklyControl\Request;
use Angorb\TwinklyControl\Timer;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{

    private Request $request;

    protected function setUp(): void
    {
        $this->request = new Request($_ENV['TREE_IP']);
        self::assertInstanceOf('Angorb\TwinklyControl\Request', $this->request);
    }

    public function testLogout(): void
    {
        self::assertEquals(Request::OK, $this->request->logout());
    }

    public function testVerify(): void
    {
        self::assertTrue($this->request->verify());
    }

    public function testGetDeviceDetails(): void
    {
        $response = $this->request->getDeviceDetails();

        self::assertIsArray($response);
        self::assertEquals(Request::OK, $response['code']);
        self::assertArrayHasKey('product_name', $response);
    }

    public function testGetFirmwareVersion(): void
    {
        $response = $this->request->getFirmwareVersion();

        self::assertIsArray($response);
        self::assertEquals(Request::OK, $response['code']);
        self::assertIsString($response['version']);
    }

    public function testGetDeviceName(): void
    {
        $response = $this->request->getDeviceName();

        self::assertIsArray($response);
        self::assertEquals(Request::OK, $response['code']);
        self::assertIsString($response['name']);
    }

    public function testSetDeviceName(): void
    {
        $initState = $this->request->getDeviceName();

        $testName = "Test Name";
        $response = $this->request->setDeviceName($testName);
        self::assertEquals(Request::OK, $response['code']);

        $testResponse = $this->request->getDeviceName();
        self::assertEquals($testName, $testResponse['name']);
        // reset to pre-test state
        $this->request->setDeviceName($initState['name']);
    }

    public function testGetTimer(): void
    {
        $response = $this->request->getTimer();

        self::assertIsArray($response);
        self::assertCount(3, $response);
    }

    public function testSetTimer(): void
    {
        // TODO undo test effects //
        $response = $this->request->setTimer(600, 2100);

        self::assertIsArray($response);
        self::assertCount(1, $response);
        self::assertEquals(Request::OK, $response['code']);

        $timerCheck = $this->request->getTimer();
        self::assertEquals(Timer::getTime(600), $timerCheck['time_on']);
        self::assertEquals(Timer::getTime(2100), $timerCheck['time_off']);
        self::assertLessThanOrEqual(Timer::now(), $timerCheck['time_now']);
    }

    public function testDisableTimer(): void
    {
        $response = $this->request->disableTimer();

        self::assertIsArray($response);
        self::assertCount(1, $response);
        self::assertEquals(Request::OK, $response['code']);

        $timerCheck = $this->request->getTimer();
        self::assertEquals(-1, $timerCheck['time_on']);
        self::assertEquals(-1, $timerCheck['time_off']);
        self::assertLessThanOrEqual(Timer::now(), $timerCheck['time_now']);
    }

    public function testSetMode(): void
    {
        $response = $this->request->setMode(Request::MODE_OFF);
        self::assertIsArray($response);
        self::assertCount(1, $response);
        self::assertEquals(Request::OK, $response['code']);

        $response = $this->request->setMode(Request::MODE_MOVIE);
        self::assertIsArray($response);
        self::assertCount(1, $response);
        self::assertEquals(Request::OK, $response['code']);
    }

    public function testGetBrightness(): void
    {
        $response = $this->request->getBrightness();

        self::assertIsArray($response);
        self::assertCount(3, $response);
        self::assertEquals(Request::OK, $response['code']);
        self::assertIsInt($response['value']);
    }

    public function testSetBrightness(): void
    {
        $initialBrightness = $this->request->getBrightness();
        do {
            // ensure we get a different value than current state //
            $testBrightnessValue = \random_int(0, 100);
        } while ($testBrightnessValue === $initialBrightness['value']);

        $response = $this->request->setBrightness($testBrightnessValue);

        self::assertIsArray($response);
        self::assertCount(1, $response);
        self::assertEquals(Request::OK, $response['code']);

        $testBrightnessResponse = $this->request->getBrightness();
        self::assertNotEquals($initialBrightness['value'], $testBrightnessResponse['value']);
        self::assertEquals($testBrightnessValue, $testBrightnessResponse['value']);
    }
}
