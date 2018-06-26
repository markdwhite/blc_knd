<?php
declare(strict_types=1);

namespace Somsip\BlcKnd\Console;

use Illuminate\Console\Command;

use Log;

/**
 * BookGorilla CommandWithHealthcheck
 *
 * Extends Command to ping Healthcheck
 *
 * @author Mark White mark@somsip.com>
 * @copyright 2018 Somsip.com
 * @package Somsip\BlcKnd
 */
class CommandWithHealthcheck extends Command
{
    /**
     * Max number of attempts before failing
     * @var int
     */
    const MAX_ATTEMPTS = 2;

    /**
     * Trackes attempts to update cronjob
     * @var int
     */
    private $attempts = 0;

    /**
     * Pings the healthcheck server
     *
     * @return void
     * @codeCoverageIgnore
     */
    protected function pingHealthcheck()
    {
        if (app()->environment('production') && config('app.healthcheckUrl')) {
            $url = sprintf(
                '%s/cronjob?ip=%s&name=%s',
                config('app.healthcheckUrl'),
                getLocalIp(),
                $this->signature
            );
            do {
                // Will timeout at 60 seconds otherwise
                $opts = [
                    'http' => ['timeout' => 10]
                ];
                $context  = stream_context_create($opts);
                $this->attempts++;
                $result = @file_get_contents($url, false, $context);
            } while (!$result && $this->attempts < self::MAX_ATTEMPTS);

            // Log an error is still unsuccessful
            if (!$result) {
                Log::error('ping healthcheck failed ' . get_called_class());
            }
        }
    }
}
