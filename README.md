# Twinkly Control
Control your Twinkly&trade; LED Christmas tree with PHP!

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