<?php

namespace Somsip\BlcKnd\Testing;

use Symfony\Component\Process\Process;

trait RunsWebServer
{
    /**
     * @var Process
     */
    protected static $webServerProcess;

    /**
     * Start the web server process
     */
    public static function startWebServer()
    {
        static::$webServerProcess = static::buildServerProcess();
        static::$webServerProcess->start();
        static::afterClass(function () {
            static::stopWebServer();
        });
    }

    /**
     * Stop the web server process
     */
    public static function stopWebServer()
    {
        if (static::$webServerProcess) {
            static::$webServerProcess->stop();
        }
    }

    /**
     * Build the process to run the web server
     *
     * @return \Symfony\Component\Process\Process
     * @throws \Symfony\Component\Process\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\LogicException
     */
    protected static function buildServerProcess()
    {
        $host = env('TEST_SERVER_HOST', 'localhost');
        $port = env('TEST_SERVER_PORT', 8000);

        $command = PHP_BINARY . " -d variables_order=EGPCS -S $host:$port -t public -f server.php";
        return Process::fromShellCommandLine(
            $command,
            getcwd(),
            [
                'APP_ENV' => env('APP_ENV'),
                'APP_URL' => env('APP_URL'),
                'DB_CONNECTION' => env('DB_CONNECTION'),
            ],
            null,
            null
        );
    }
}
