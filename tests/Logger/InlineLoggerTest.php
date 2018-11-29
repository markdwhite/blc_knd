<?php

use Somsip\BlcKnd\Logger\InlineLogger;

use Illuminate\Log\Logger;

use Monolog\Handler\StreamHandler;

use Orchestra\Testbench\TestCase;

class InlineLoggerTest extends TestCase
{
    public function testInvoke()
    {
        $inline = new InlineLogger();

        $mockHandler = Mockery::mock(StreamHandler::class);
        $mockHandler->shouldReceive('setFormatter')
            ->once();

        $mockLogger = Mockery::mock(Logger::class)->makePartial();
        $mockLogger->shouldReceive('pushProcessor')
            ->once();
        $mockLogger->shouldReceive('getHandlers')
            ->once()
            ->andReturn([$mockHandler]);

        $inline($mockLogger);
    }
}
