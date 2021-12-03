<?php

namespace Angorb\TwinklyControl\Exception;

class InvalidAddressException extends \Exception
{
    public function __construct($address)
    {
        $this->message = "{$address} is not a valid IP address.";
    }
}
