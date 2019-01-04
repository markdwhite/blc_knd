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
 * [2000-01-01 00:00:00] app.DEBUG: Class::method() Test message
 *
 * Function call
 * [2000-01-01 00:00:00] app.DEBUG: function() Test message
 *
 * Class but method not identified
 * [2000-01-01 00:00:00] app.DEBUG: Class::{undefined}() Test message
 *
 * No class or function identified
 * [2000-01-01 00:00:00] app.DEBUG: Test message
 *
 * @author Mark White <mark@somsip.com>
 * @package Somsip\BlcKnd
 */
class SentryCallerInlineFormatter extends CallerInlineFormatter
{
    const SIMPLE_FORMAT = "%message% %context%\n";
}
