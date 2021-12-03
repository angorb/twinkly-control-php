# Twinkly Control
*Control your Twinkly&trade; LED lights  with PHP!*

I got bored one night in December 2020 and read a dissection of the REST protocol Twinkly uses. This was the result of a couple cocktails and some Netflix, so it's not 'enterprise-grade' but it was a fun tinkering project.

My tree is packed away at the moment so I probably wont be making any updates to this until November of this year. If you tinker with this at all feel free to open a PR; changes and improvements are always welcomed. If you build something cool with it, I want to see!

### Update 2021:
I cleaned up some bugs (*I blame the egg nog...*) and made some changes to split the original `TwinklyControl` class into it's respective `TwinklyControl` and `Request` classes.

A `TwinklyControl` now represents a specific instance of a Twinkly light set and it's properties while `Request` only handles the API communication.

When creating a new `TwinklyControl` the device's properties are set **once** when it successfully authenticates. From my testing (which is admittedly limited to my single Gen 1 600 LED tree), it appears that these properties are not updated until the the next API login.

For example, RSSI is one value that fluctuates all the time based on the wireless signal from your AP, but until you hit the 'logout' endpoint and log back in the value will not be updated.

Properties of the `TwinklyControl` class accessible via the device details endpoint have been made "readonly" in a hacky, pre-PHP 8 way until 8 becomes more widely adopted.

I've also included some basic PHPUnit test cases.
## Usage:
```php
<?php

use Angorb\TwinklyControl\TwinklyControl;

require_once __DIR__ . '/../vendor/autoload.php';

// Create a new instance of TwinklyControl
$control = new TwinklyControl('{YOUR TWINKLY DEVICE IP}');

// Get device information
echo $control->number_of_led;   // print the number of LEDs
echo $control->mac;             // print the MAC address
echo $control->rssi;            // print the recived signal strength

// Calling TwinklyControl methods with a null argument gets the value...
echo $control->name();          // print the 'friendly' tree name

// ... while passing an argument sets the value
$control->name("Santas Little Helper"); // Rename your tree

// set the timer on/off times (5pm - 10pm)
$control->timer(1700, 2200);

// get the current brightness;
$curBrightness = $control->brightness();

// set the brightness to 50%
$control->brightness(50);
