<?php

use Somsip\BlcKnd\Logger\Formatter\SentryCallerInlineFormatter;

use Orchestra\Testbench\TestCase;

class SentryCallerInlineFormatterTest extends TestCase
{
    public function testNew()
    {
        $formatter = new SentryCallerInlineFormatter();

        $this->assertInstanceOf(SentryCallerInlineFormatter::class, $formatter);
    }

    public function testFormatDefault()
    {
        $formatter = new SentryCallerInlineFormatter();
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

        $this->assertEquals('Class::test() Test message []', $result);
    }

    public function testFormatDefaultNoClassNamespace()
    {
        $formatter = new SentryCallerInlineFormatter();
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

        $this->assertEquals('Class::test() Test message []', $result);
    }

    public function testFormatMissingClass()
    {
        $formatter = new SentryCallerInlineFormatter();
        $extra = [
                'function' => 'test',
        ];
        $record = $this->mockRecord($extra);

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('test() Test message []', $result);
    }

    public function testFormatEmptyClass()
    {
        $formatter = new SentryCallerInlineFormatter();
        $extra = [
                'class' => '',
                'function' => 'test',
        ];
        $record = $this->mockRecord($extra);

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('test() Test message []', $result);
    }

    public function testFormatMissingFunction()
    {
        $formatter = new SentryCallerInlineFormatter();
        $extra = [
            'class' => '\\Tests\Unit\\Class',
        ];
        $record = $this->mockRecord($extra);

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('Class::{undefined}() Test message []', $result);
    }

    public function testFormatEmptyFunction()
    {
        $formatter = new SentryCallerInlineFormatter();
        $extra = [
            'class' => '\\Tests\Unit\\Class',
            'function' => '',
        ];
        $record = $this->mockRecord($extra);

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('Class::{undefined}() Test message []', $result);
    }

    public function testFormatNoExtras()
    {
        $formatter = new SentryCallerInlineFormatter();
        $record = $this->mockRecord();

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('Test message []', $result);
    }

    public function testFormatWithContext()
    {
        $formatter = new SentryCallerInlineFormatter();
        $record = $this->mockRecord();
        $record['context'] = [
            'test' => 'context'
        ];

        $result = $formatter->format($record);
        // Trim trailing newline
        $result = trim($result);

        $this->assertEquals('Test message {"test":"context"}', $result);
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
