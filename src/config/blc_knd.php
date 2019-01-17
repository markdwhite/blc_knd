<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Critical email recipients
    |--------------------------------------------------------------------------
    */

    'critical' => [
        'mark@somsip.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | URL to ping for completed cronjobs
    |--------------------------------------------------------------------------
    */

    'healthcheck_url' => env('HEALTHCHECK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Enable MySQL logging
    |--------------------------------------------------------------------------
    */

    'log_sql' => env('LOG_SQL'),
    'log_sql_params' => env('LOG_SQL_PARAMS'),

];
