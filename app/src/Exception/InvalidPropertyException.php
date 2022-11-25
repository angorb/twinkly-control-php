<?php

namespace Angorb\TwinklyControl\Exception;

class InvalidPropertyException extends \Exception
{
    public function __construct($name)
    {
        $this->message = "Property {$name} does not exist.";
    }
}
