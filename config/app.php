<?php

return [

    'name' => env('APP_NAME', 'Device Tracker'),

    'env' => env('APP_ENV', 'production'),

    'chart_datetime_format' => env('CHART_DATETIME_FORMAT', 'j/m'),

    'datetime_format' => env('DATETIME_FORMAT', 'M. jS, Y g:ia'),

    'display_timezone' => env('DISPLAY_TIMEZONE', 'Europe/Amsterdam'),

    'force_https' => env('FORCE_HTTPS', false),

    'chart_default_date_range' => env('CHART_DEFAULT_DATE_RANGE', 120),

];
