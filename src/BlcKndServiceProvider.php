<?php
declare(strict_types=1);

namespace Somsip\BlcKnd;

use Somsip\BlcKnd\Logger\Formatter\CallerInlineFormatter;

use Illuminate\Support\ServiceProvider;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RavenHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

use Log;
use Raven_Client;

class BlcKndServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'blc_knd');

        $this->publishes([
            __DIR__.'/config/blc_knd.php' => config_path('blc_knd.php'),
        ]);
    }

    /**
     * Register the logger
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function register()
    {
        // Setup some custom logging
        $monolog = Log::getMonolog();
        // Change the default formatter
        $monolog->getHandlers()[0]->setFormatter(new CallerInlineFormatter());
        // Get all output from logger, but ignore references to non-app classes
        $ignores = [
            'Writer',
            'Facade'
        ];
        $monolog->pushProcessor(new IntrospectionProcessor(Logger::DEBUG, $ignores));

        if (app()->environment('production')) {
            // Email critical errors to admin
            $monolog->pushHandler(
                new LaravelMailerHandler(
                    config('blc_knd.critical'),
                    sprintf('%s %s %s: CRITICAL ERROR encountered', config('app.name'), app()->environment(), getLocalIp()),
                    Logger::CRITICAL
                )
            );
            // Log errors to sentry
            if (config('services.raven.dsn')) {
                $monolog = Log::getMonolog();
                $client = new Raven_Client(config('services.raven.dsn'));
                $handler = new RavenHandler(
                    $client,
                    Logger::ERROR
                );
                $handler->setFormatter(new LineFormatter("%message% %context% %extra%\n"));
                $monolog->pushHandler($handler);
            }
        }
    }
}
