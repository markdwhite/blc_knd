<?php
declare(strict_types=1);

namespace Somsip\BlcKnd\Logger;

use Illuminate\Log\Logger;

use Monolog\Processor\IntrospectionProcessor;
use Monolog\Logger as Monolog;

use Somsip\BlcKnd\Logger\Formatter\CallerInlineFormatter;

class InlineLogger
{
    /**
     * Customize the given logger instance.
     *
     * @param \Illuminate\Log\Logger $logger
     * @return void
     */
    public function __invoke(Logger $logger)
    {
        // If we do this here, we can tap into any log channel
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
