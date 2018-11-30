<?php

use Somsip\BlcKnd\Logger\Handlers\CriticalMailHandler;
use Somsip\BlcKnd\Mail\CriticalError;

use Monolog\Logger;

use Orchestra\Testbench\TestCase;

class CriticalMailHandlerTest extends TestCase
{
    // Ensure config is loaded and all Facades are available
    protected function getPackageProviders($app)
    {
        return ['Somsip\BlcKnd\ServiceProvider'];
    }

    public function testNew()
    {
        $handler = new CriticalMailHandler('to', 'subject');

        $this->assertInstanceOf(CriticalMailHandler::class, $handler);
    }

    public function testSendNotThrottled()
    {
        $subject = sprintf('Laravel %s %s: CRITICAL ERROR encountered', app()->environment(), '127.0.0.1');
        $filename = 'email_throttle_' . md5($subject);
        Storage::delete($filename);

        $handler = new CriticalMailHandler();
        $content = 'This is a test';

        // Make protected method public
        $method = $this->getAccessibleMethod(get_class($handler), 'send');

        Mail::fake();

        $method->invoke($handler, $content, []);

        Mail::assertSent(CriticalError::class, function ($mailable) {
            return $mailable->hasTo(config('blc_knd.critical')[0])
                && ($mailable->subject == 'Laravel testing 127.0.0.1: CRITICAL ERROR encountered');
        });
        $this->assertTrue(Storage::has($filename));
        $this->assertTrue(View::exists('blc_knd::emails.error'));

        Storage::delete($filename);
    }

    public function testSendThrottled()
    {
        $subject = sprintf('Laravel %s %s: CRITICAL ERROR encountered', app()->environment(), '127.0.0.1');
        $filename = 'email_throttle_' . md5($subject);
        Storage::put($filename, 'test');

        $handler = new CriticalMailHandler();
        $content = 'This is a test';

        // Make protected method public
        $method = $this->getAccessibleMethod(get_class($handler), 'send');

        $method->invoke($handler, $content, []);

        Mail::shouldReceive('to')
            ->never();

        Storage::delete($filename);
    }

    /**
     * Allow testing of private and protected methods
     *
     * @param string $class
     * @param string $method
     * @return ReflectionMethod
     */
    private function getAccessibleMethod($class, $method)
    {
        $reflection = new ReflectionClass($class);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);
        return $method;
    }
}
