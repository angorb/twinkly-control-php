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
        $this->request = new Request('192.168.1.237');
        $this->assertInstanceOf('Angorb\TwinklyControl\Request', $this->request);
    }

    public function testLogout(): void
    {
        $this->assertEquals(Request::OK, $this->request->logout());
    }

    public function testVerify(): void
    {
        $this->assertTrue($this->request->verify());
    }

    public function testGetDeviceDetails(): void
    {
        $response = $this->request->getDeviceDetails();

        $this->assertIsArray($response);
        $this->assertEquals(Request::OK, $response['code']);
        $this->assertArrayHasKey('product_name', $response);
    }

    public function testGetFirmwareVersion(): void
    {
        $response = $this->request->getFirmwareVersion();

        $this->assertIsArray($response);
        $this->assertEquals(Request::OK, $response['code']);
        $this->assertIsString($response['version']);
    }

    public function testGetDeviceName(): void
    {
        $response = $this->request->getDeviceName();

        $this->assertIsArray($response);
        $this->assertEquals(Request::OK, $response['code']);
        $this->assertIsString($response['name']);
    }

    public function testSetDeviceName(): void
    {
        $initState = $this->request->getDeviceName();

        $testName = "Test Name";
        $response = $this->request->setDeviceName($testName);
        $this->assertEquals(Request::OK, $response['code']);

        $testResponse = $this->request->getDeviceName();
        $this->assertEquals($testName, $testResponse['name']);
        // reset to pre-test state
        $this->request->setDeviceName($initState['name']);
    }

    public function testGetTimer(): void
    {
        $response = $this->request->getTimer();

        $this->assertIsArray($response);
        $this->assertCount(3, $response);
    }

    public function testSetTimer(): void
    {
        // TODO undo test effects //
        $response = $this->request->setTimer(600, 2100);

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
        $this->assertEquals(Request::OK, $response['code']);

        $timerCheck = $this->request->getTimer();
        $this->assertEquals(Timer::getTime(600), $timerCheck['time_on']);
        $this->assertEquals(Timer::getTime(2100), $timerCheck['time_off']);
        $this->assertLessThanOrEqual(Timer::now(), $timerCheck['time_now']);
    }

    public function testDisableTimer(): void
    {
        $response = $this->request->disableTimer();

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
        $this->assertEquals(Request::OK, $response['code']);

        $timerCheck = $this->request->getTimer();
        $this->assertEquals(-1, $timerCheck['time_on']);
        $this->assertEquals(-1, $timerCheck['time_off']);
        $this->assertLessThanOrEqual(Timer::now(), $timerCheck['time_now']);
    }
}
