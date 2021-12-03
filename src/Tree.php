<?php

namespace Angorb\TwinklyControl;

use Angorb\TwinklyControl\Exception\InvalidAddressException;
use Angorb\TwinklyControl\Exception\InvalidPropertyException;

class Tree
{

    public const DISABLE_TIMER = -1;

    private Request $control;
    private int $code;

    # TREE PROPERTIES
    private string $product_name;
    private string $product_version;
    private string $hardware_version;
    private string $driver_version;
    private int $flash_size;
    private int $led_type;
    private string $led_version;
    private string $product_code;
    private string $device_name;
    private string $uptime;
    private int $rssi;
    private string $hw_id;
    private string $mac;
    private string $uuid;
    private int $max_supported_led;
    private int $base_leds_number;
    private int $number_of_led;
    private string $led_profile;
    private int $frame_rate;
    private int $movie_capacity;
    private string $copyright;

    public function __construct(string $ip)
    {
        if (\false === \filter_var($ip, \FILTER_VALIDATE_IP)) {
            throw new InvalidAddressException($ip);
        }
        $this->control = new Request($ip);
        $this->updateDeviceDetails();
    }

    public function __destruct()
    {
        $this->control->logout();
    }

    public function __set($name, $value)
    {
        return; // fake readonly properties until 8.1 is more widely adopted
    }

    public function __get($name)
    {
        if (\property_exists($this, $name)) {
            if (\method_exists($this, $name)) {
                return $this->$name();
            }
            return $this->$name;
        }
        throw new InvalidPropertyException($name);
    }

    private function updateDeviceDetails(): void
    {
        // load initial device details //
        $properties = $this->control->getDeviceDetails();
        foreach ($properties as $name => $value) {
            $this->$name = $value;
        }
    }

    public function brightness(?int $brightness = null)
    {
        // get brightness
        if (\is_null($brightness)) {
            $brightness = $this->control->getBrightness();
            return $brightness['value'];
        }

        // set brightness
        if ($brightness > 100) {
            $brightness = 100;
        } elseif ($brightness < 0) {
            $brightness = 0;
        }

        $this->control->setBrightness($brightness);
    }

    public function name(?int $name = null)
    {
        // get device name
        if (\is_null($name)) {
            $name = $this->control->getDeviceName();
            return $name['name'];
        }

        // set device name
        if (\false === empty($name)) {
            $this->control->setDeviceName($name);
        }
    }

    public function timer(?int $start_time = null, ?int $stop_time = null)
    {
        // get timer
        if (\is_null($start_time) && \is_null($stop_time)) {
            return $this->control->getTimer(); // TODO return values //
        }

        // disable timer
        if ($start_time === self::DISABLE_TIMER) {
            $this->control->disableTimer();
        }

        // set timer
        if (\is_null($start_time) || \is_null($stop_time)) {
            $currentTimer = $this->control->getTimer();
            $start_time = \is_null($start_time) ? $currentTimer['start_time'] : $start_time;
            $stop_time = \is_null($stop_time) ? $currentTimer['stop_time'] : $stop_time;
        }
        $this->control->setTimer(
            Timer::ensureValid($start_time),
            Timer::ensureValid($stop_time)
        );
    }
}
