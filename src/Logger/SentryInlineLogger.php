<?php
declare(strict_types=1);

namespace Somsip\BlcKnd\Logger;

use Somsip\BlcKnd\Logger\Formatter\CallerInlineFormatter;
use Somsip\BlcKnd\Logger\Formatter\SentryCallerInlineFormatter;

class SentryInlineLogger extends InlineLogger
{
    /**
     * Returns the formatter to be used
     *
     * @return \Somsip\BlcKnd\Logger\Formatter\CallerInlineFormatter
     */
    protected function getFormatter(): CallerInlineFormatter
    {
        return new SentryCallerInlineFormatter();
    }
}
