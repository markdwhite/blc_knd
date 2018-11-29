<?php

use Somsip\BlcKnd\Mail\Handlers\LaravelMailerHandler;
use Somsip\BlcKnd\Mail\CriticalError;

use Monolog\Logger;

use Orchestra\Testbench\TestCase;

class LaravelMailerHandlerTest extends TestCase
{
    // Ensure config is loaded and all Facades are available
    protected function getPackageProviders($app)
    {
        return ['Somsip\BlcKnd\ServiceProvider'];
    }

    public function testNew()
    {
        $handler = new LaravelMailerHandler('to', 'subject');

        $this->assertInstanceOf(LaravelMailerHandler::class, $handler);
    }

    public function testSendNotThrottled()
    {
        $subject = sprintf('App %s %s: CRITICAL ERROR encountered', app()->environment(), '127.0.0.1');
        $filename = 'email_throttle_' . md5($subject);
        Storage::delete($filename);

        $handler = new LaravelMailerHandler(
            config('blc_knd.critical'),
            $subject,
            Logger::CRITICAL
        );
        $content = 'This is a test';

        // Make protected method public
        $method = $this->getAccessibleMethod(get_class($handler), 'send');

        Mail::fake();

        $method->invoke($handler, $content, []);

        Mail::assertSent(CriticalError::class, function ($mailable) {
            return $mailable->hasTo(config('blc_knd.critical')[0])
                && ($mailable->subject == 'App testing 127.0.0.1: CRITICAL ERROR encountered');
        });
        $this->assertTrue(Storage::has($filename));
        $this->assertTrue(View::exists('blc_knd::emails.error'));

        Storage::delete($filename);
    }

    public function testSendThrottled()
    {
        $subject = sprintf('App %s %s: CRITICAL ERROR encountered', app()->environment(), '127.0.0.1');
        $filename = 'email_throttle_' . md5($subject);
        Storage::put($filename, 'test');

        $handler = new LaravelMailerHandler(
            config('blc_knd.critical'),
            $subject,
            Logger::CRITICAL
        );
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
