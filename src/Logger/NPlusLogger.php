<?php

namespace Somsip\BlcKnd\Logger;

use BeyondCode\QueryDetector\Outputs\Output;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use Log as LaravelLog;

/**
 * Will log all N+1 queries identified by QueryDetector as critical. With a normal config this will send an
 * immediate email and log the error to Sentry
 *
 * Enable query detection in .env
 * QUERY_DETECTOR_ENABLED=true
 *
 * Add entry to config/querydetector.php under 'output' section
 *     \Somsip\BlcKnd\Logger\NPlusLogger::class,
 *
 * @codeCoverageIgnore
 */
class NPlusLogger implements Output
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function output(Collection $detectedQueries, Response $response)
    {
        LaravelLog::info('Detected N+1 Query');

        foreach ($detectedQueries as $detectedQuery) {
            $logOutput = 'Model: '.$detectedQuery['model'] . PHP_EOL;
            
            $logOutput .= 'Relation: '.$detectedQuery['relation'] . PHP_EOL;

            $logOutput .= 'Num-Called: '.$detectedQuery['count'] . PHP_EOL;
            
            $logOutput .= 'Call-Stack:' . PHP_EOL;

            foreach ($detectedQuery['sources'] as $source) {
                $logOutput .= '#'.$source->index.' '.$source->name.':'.$source->line . PHP_EOL;
            }

            LaravelLog::critical($logOutput);
        }
    }
}
