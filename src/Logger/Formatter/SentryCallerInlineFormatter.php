<?php

namespace Somsip\BlcKnd\Logger\Formatter;

use Monolog\Formatter\LineFormatter;

/**
 * Formats incoming records into a one-line string prefixed by the calling Class::method(), but without
 * datetime, channel and level for use with Sentry
 *
 * Examples:
 *
 * Class and method
 * Class::method() Test message
 *
 * Function call
 * function() Test message
 *
 * Class but method not identified
 * Class::{undefined}() Test message
 *
 * No class or function identified
 * Test message
 *
 * @author Mark White <mark@somsip.com>
 * @package Somsip\BlcKnd
 */
class SentryCallerInlineFormatter extends CallerInlineFormatter
{
    const SIMPLE_FORMAT = "%message% %context%\n";
}
