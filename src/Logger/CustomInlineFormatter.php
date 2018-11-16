<?php
declare(strict_types=1);

namespace Somsip\BlcKnd\Logging;

use Illuminate\Log\Logger;

use Monolog\Processor\IntrospectionProcessor;
use Monolog\Logger as Monolog;

use Somsip\BlcKnd\Logger\Formatter\CallerInlineFormatter;

class CustomInlineFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke(Logger $logger)
    {
        // FIXME: This needs to happen somewhere else, like a custom logger
        $ignores = [
            'Facade',
            'Logger',
            'LogManager'
        ];
        $logger->pushProcessor(new IntrospectionProcessor(Monolog::DEBUG, $ignores));

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new CallerInlineFormatter());
        }
    }
}
