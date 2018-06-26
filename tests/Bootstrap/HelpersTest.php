<?php

use Orchestra\Testbench\TestCase;

class HelpersTest extends TestCase
{
    public function testGetLocalIp()
    {
        $ip = getLocalIp();

        $this->assertRegExp('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $ip);
    }
}
