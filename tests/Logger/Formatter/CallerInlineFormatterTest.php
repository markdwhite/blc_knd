<?php

use Somsip\BlcKnd\Logger\Formatter\CallerInlineFormatter;

class CallerInlineFormatterTest extends PHPUnit_Framework_TestCase
{
    public function testNew()
    {
        $formatter = new CallerInlineFormatter();

        $this->assertInstanceOf(CallerInlineFormatter::class, $formatter);
    }

    public function testFormatDefault()
    {
        $formatter = new CallerInlineFormatter();
        $extra = [
                'class' => '\\Tests\Unit\\Class',
                'function' => 'test',
                'line' => '123',
                'file' => '/var/www/test.php'
        ];
        $record = $this->mockRecord($extra);

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('[2000-01-01 00:00:00] phpunit.TEST: Class::test() Test message []', $result);
    }

    public function testFormatDefaultNoClassNamespace()
    {
        $formatter = new CallerInlineFormatter();
        $extra = [
                'class' => 'Class',
                'function' => 'test',
                'line' => '123',
                'file' => '/var/www/test.php'
        ];
        $record = $this->mockRecord($extra);

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('[2000-01-01 00:00:00] phpunit.TEST: Class::test() Test message []', $result);
    }

    public function testFormatMissingClass()
    {
        $formatter = new CallerInlineFormatter();
        $extra = [
                'function' => 'test',
        ];
        $record = $this->mockRecord($extra);

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('[2000-01-01 00:00:00] phpunit.TEST: test() Test message []', $result);
    }

    public function testFormatEmptyClass()
    {
        $formatter = new CallerInlineFormatter();
        $extra = [
                'class' => '',
                'function' => 'test',
        ];
        $record = $this->mockRecord($extra);

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('[2000-01-01 00:00:00] phpunit.TEST: test() Test message []', $result);
    }

    public function testFormatMissingFunction()
    {
        $formatter = new CallerInlineFormatter();
        $extra = [
            'class' => '\\Tests\Unit\\Class',
        ];
        $record = $this->mockRecord($extra);

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('[2000-01-01 00:00:00] phpunit.TEST: Class::{undefined}() Test message []', $result);
    }

    public function testFormatEmptyFunction()
    {
        $formatter = new CallerInlineFormatter();
        $extra = [
            'class' => '\\Tests\Unit\\Class',
            'function' => '',
        ];
        $record = $this->mockRecord($extra);

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('[2000-01-01 00:00:00] phpunit.TEST: Class::{undefined}() Test message []', $result);
    }

    public function testFormatNoExtras()
    {
        $formatter = new CallerInlineFormatter();
        $record = $this->mockRecord();

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('[2000-01-01 00:00:00] phpunit.TEST: Test message []', $result);
    }

    public function testFormatWithContext()
    {
        $formatter = new CallerInlineFormatter();
        $record = $this->mockRecord();
        $record['context'] = [
            'test' => 'context'
        ];

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('[2000-01-01 00:00:00] phpunit.TEST: Test message {"test":"context"}', $result);
    }

    /**
     * Provides a mock record for use in other tests
     *
     * @param array $extra
     * @return array
     */
    private function mockRecord(array $extra = [])
    {
        return [
            'message' => 'Test message',
            'channel' => 'phpunit',
            'datetime' => '2000-01-01 00:00:00',
            'level_name' => 'TEST',
            'context' => [],
            'extra' => $extra
        ];
    }
}
