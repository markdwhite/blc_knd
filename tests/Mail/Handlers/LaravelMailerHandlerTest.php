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

    public function testTest()
    {
        $this->assertTrue(true);
    }

//    public function testSendNotThrottled()
//    {
//        $subject = sprintf('App %s %s: CRITICAL ERROR encountered', app()->environment(), '127.0.0.1');
//        $filename = 'email_throttle_' . md5($subject);
//
//        $monolog = Log::getMonolog();
//        $monolog->pushHandler(
//            new LaravelMailerHandler(
//                config('blc_knd.critical'),
//                $subject,
//                Logger::CRITICAL
//            )
//        );
//        $content = 'This is a test';
//
//        Mail::fake();
//
//        Log::critical($content);
//
//        Mail::assertSent(CriticalError::class, function ($mailable) {
//            return $mailable->hasTo(config('blc_knd.critical')[0])
//                && ($mailable->subject == 'App testing 127.0.0.1: CRITICAL ERROR encountered');
//        });
//        $this->assertTrue(Storage::has($filename));
//        $this->assertTrue(View::exists('blc_knd::emails.error'));
//
//        Storage::delete($filename);
//    }
//
//    public function testSendThrottled()
//    {
//        $subject = sprintf('App %s %s: CRITICAL ERROR encountered', app()->environment(), '127.0.0.1');
//        $filename = 'email_throttle_' . md5($subject);
//        Storage::put($filename, 'test');
//        $monolog = Log::getMonolog();
//        $monolog->pushHandler(
//            new LaravelMailerHandler(
//                config('blc_knd.critical'),
//                $subject,
//                Logger::CRITICAL
//            )
//        );
//        $content = 'This is a test';
//
//        Log::critical($content);
//
//        Mail::shouldReceive('to')
//            ->never();
//
//        Storage::delete($filename);
//    }
}
