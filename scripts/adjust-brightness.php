<?php

use Angorb\TwinklyControl\TwinklyControl;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';


if (empty($argv[1])) {
    die('Brightness value must be provided.');
}

if (!is_numeric($argv[1])) {
    die('Brightness value must be numeric.');
}

$brightness = intval($argv[1]);

if ($brightness > 100) {
    $brightness = 100;
}

if ($brightness < 0) {
    $brightness = 0;
}

echo 'Setting device brightness to ' . $brightness . '%' . PHP_EOL;

// Create a new instance of TwinklyControl
$control = new TwinklyControl(TWINKLY_IP);
$control->brightness($brightness);
