<?php
declare(strict_types=1);

namespace Somsip\BlcKnd\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Logger;

use Somsip\BlcKnd\Logger\Formatter\CallerInlineFormatter;

class InlineLogger
{
    /**
     * Customize the given logger instance.
     *
     * @param array $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config): Logger
    {
        $handler = new StreamHandler($config['path'], Logger::DEBUG, true, null, false);
        $handler->setFormatter(new CallerInlineFormatter());

        $ignores = [
            'Facade',
            'Logger',
            'LogManager'
        ];
        $processor = new IntrospectionProcessor(Logger::DEBUG, $ignores);

        $logger = new Logger(app()->environment(), [$handler], [$processor]);

        return $logger;
    }
}
