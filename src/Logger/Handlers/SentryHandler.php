<?php
declare(strict_types=1);

namespace Somsip\BlcKnd\Logger\Handlers;

use Monolog\Logger;
use Monolog\Handler\RavenHandler;

use Raven_Client;

/**
 * SentryHandler logs to Sentry
 *
 * @author Mark White mark@somsip.com>
 * @copyright 2018 Somsip.com
 * @package Somsip\BlcKnd
 */
class SentryHandler extends RavenHandler
{
    /**
     * @param \Monolog\Handler\RavenHandler $
     * @param integer      $level          The minimum logging level at which this handler will be triggered
     * @param boolean      $bubble         Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct($level = Logger::ERROR, $bubble = true)
    {
        $client = new Raven_Client(config('blc_knd.sentry_url'));
        parent::__construct($client, $level, $bubble);
    }
}
