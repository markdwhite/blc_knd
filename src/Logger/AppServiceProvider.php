<?php
declare(strict_types=1);

namespace Somsip\BlcKnd\Logger;

use Illuminate\Support\ServiceProvider;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RavenHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

use Raven_Client;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Register the logger
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function register()
    {
        // Setup some custom logging
        $monolog = Log::getMonolog();
        // Change the default formatter
        $monolog->getHandlers()[0]->setFormatter(new CallerInlineFormatter());
        // Get all output from logger, but ignore references to non-app classes
        $ignores = [
            'Writer',
            'Facade'
        ];
        $monolog->pushProcessor(new IntrospectionProcessor(Logger::DEBUG, $ignores));
    }
}
