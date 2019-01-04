<?php

use Somsip\BlcKnd\Logger\SentryInlineLogger;

use Illuminate\Log\Logger;

use Monolog\Handler\StreamHandler;

use Orchestra\Testbench\TestCase;

class SentryInlineLoggerTest extends TestCase
{
    public function testInvoke()
    {
        $inline = new SentryInlineLogger();

        $mockHandler = Mockery::mock(StreamHandler::class);
        $mockHandler->shouldReceive('setFormatter')
            ->once();
        $mockHandler->shouldReceive('pushProcessor')
            ->once();

        $mockLogger = Mockery::mock(Logger::class)->makePartial();
        $mockLogger->shouldReceive('getHandlers')
            ->once()
            ->andReturn([$mockHandler]);

        $inline($mockLogger);
    }
}
