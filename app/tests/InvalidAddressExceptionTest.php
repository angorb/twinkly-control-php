<?php

namespace Angorb\TwinklyControl\Tests;

use Angorb\TwinklyControl\Exception\InvalidAddressException;
use Angorb\TwinklyControl\Request;
use PHPUnit\Framework\TestCase;

class InvalidAddressExceptionTest extends TestCase
{
    public function testExceptionThrownOnInvalidAddress(): void
    {
        $this->expectException(InvalidAddressException::class);
        new Request('h');
    }
}
