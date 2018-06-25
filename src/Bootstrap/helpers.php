<?php
declare(strict_types=1);

/**
 * Derives the IP from AWS or localhost
 *
 * @return string
 */
if (!function_exists('getLocalIp')) {
    function getLocalIp(): string
    {
        // Only retrieve this once
        static $ip = null;

        if ($ip) {
            return $ip;
        }

        if (!app()->environment('production')) {
            $timeout = 0;
        } else {
            // @codeCoverageIgnoreStart
            $timeout = 1;
            // @codeCoverageIgnoreEnd
        }

        // Localhost will timeout at 60 seconds otherwise
        $opts = [
            'http' => ['timeout' => $timeout]
        ];

        $context  = stream_context_create($opts);
        $ip = @file_get_contents('http://169.254.169.254/latest/meta-data/local-ipv4', false, $context);

        // Or set to localhost
        if (!$ip) {
            $ip = '127.0.0.1';
        }
        return $ip;
    }
}
