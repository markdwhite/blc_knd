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
        if (app()->environment('production') && config('blc_knd.healthcheck_url')) {
            $url = sprintf(
                '%s/cronjob?ip=%s&name=%s',
                config('blc_knd.healthcheck_url'),
                getLocalIp(),
                strtok($this->signature, ' ')
            );
            do {
                // Will timeout at 60 seconds otherwise
                $opts = [
                    'http' => ['timeout' => 2]
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

    /**
     * Pauses to avoid throttling
     *
     * @param int $seconds
     * @return void
     * @codeCoverageIgnore
     */
    protected function pause(int $seconds = 1)
    {
        if (!app()->environment('testing')) {
            sleep($seconds);
        }
    }
}
