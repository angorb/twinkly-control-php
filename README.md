# Twinkly Control
Control your Twinkly&trade; LED Christmas tree with PHP!

I got bored one night in December 2020 and read a dissection of the REST protocol Twinkly uses. This was the result of a couple cocktails and some Netflix, so it's not 'enterprise-grade' but it was a fun tinkering project.

My tree is packed away at the moment so I probably wont be making any updates to this until November of this year. If you tinker with this at all feel free to open a PR; changes and improvements are always welcomed. If you build something cool with it, I want to see!

##Usage:
```php
<?php

use Angorb\TwinklyControl\TwinklyControl;

require_once __DIR__ . "/../vendor/autoload.php";

// Create a new instance of TwinklyControl
$control = new TwinklyControl("YOUR TREE IP");

// Get device information
$deviceDetails = $control->getDeviceDetails();

// Rename your tree
$control->setDeviceName("Santas Little Helper");

// set the timer on/off times (5pm - 10pm)
$control->setTimer(1700, 2200);

// set the brightness to 50%
$control->setBrightness(50);