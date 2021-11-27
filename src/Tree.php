<?php

namespace Angorb\TwinklyControl;

use Angorb\TwinklyControl\Exception\InvalidAddressException;

class Tree
{

    private \Angorb\TwinklyControl\Request $control;
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
        $this->control = new Request($ip);
        $this->updateDeviceDetails();
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
            } else {
                return $this->$name;
            }
        }

        throw new \Angorb\TwinklyControl\Exception\InvalidPropertyException($name);
    }

    private function updateDeviceDetails(): void
    {
        // load initial device details //
        $properties = $this->control->getDeviceDetails();
        foreach ($properties as $name => $value) {
            $this->$name = $value;
        }
    }
}
