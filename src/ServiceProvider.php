<?php
declare(strict_types=1);

namespace Somsip\BlcKnd;

use Somsip\BlcKnd\Logger\Formatter\CallerInlineFormatter;
use Somsip\BlcKnd\Mail\Handlers\LaravelMailerHandler;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RavenHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

use DB;
use Log;
use Raven_Client;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'blc_knd');

        $this->publishes([
            __DIR__.'/config/blc_knd.php' => config_path('blc_knd.php'),
        ]);

        // Log all DB SELECT statements to check indexes
        // Parse with: grep ") sql:" laravel.log | sed -e "s#.*select\(.*\)\[\]#select\1#" | sort -u
        // @codeCoverageIgnoreStart
        if (!app()->environment('testing') && config('blc_knd.log_sql')) {
            DB::listen(function ($query) {
                if (preg_match('/^select/', $query->sql)) {
                    Log::info('sql: ' .  $query->sql);
                }
            });
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Register the logger
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/blc_knd.php', 'blc_knd');
    }
}
