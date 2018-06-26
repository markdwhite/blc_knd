<?php

use Somsip\BlcKnd\Mail\CriticalError;

use Orchestra\Testbench\TestCase;

class CriticalErrorTest extends TestCase
{
    public function testNew()
    {
        $mail = new CriticalError('test subject', 'test content');

        $this->assertInstanceOf(CriticalError::class, $mail);
    }

    public function testBuild()
    {
        $content = 'test content';
        $subject = 'test subject';
        $mail = new CriticalError($subject, $content);

        $result = $mail->build();

        $this->assertEquals($subject, $result->subject);
        $this->assertEquals($content, $result->content);
        $this->assertEquals('blc_knd::emails.error', $result->view);
    }
}
