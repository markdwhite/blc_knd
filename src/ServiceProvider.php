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
        // Parse with: grep "sql:" sql.log | sed -e "s#.*sql: \(.*\)\[\]#select\1#" | sort | uniq -c | sort -bgr
        // @codeCoverageIgnoreStart
        if (config('blc_knd.log_sql')) {
            // Use file_put_contents() when testing as Log causes problems with expectations on Log::shouldReceive()
            if (app()->environment('testing')) {
                DB::listen(function ($query) {
                    $sql = (string) $query->sql;
                    if (preg_match('/^\s*select/i', $sql)) {
                        file_put_contents(storage_path('logs') . '/sql.log', 'sql: ' . $sql . PHP_EOL, FILE_APPEND);
                    }
                });
            } else {
                DB::listen(function ($query) {
                    $sql = (string) $query->sql;
                    if (preg_match('/^\s*select/i', $sql)) {
                        Log::info('sql: ' .  $sql);
                    }
                });
            }
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
