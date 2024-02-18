<?php

return [
    'channels' => [
        
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily','slack'],
            'ignore_exceptions' => false,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
            'replace_placeholders' => true,
        ],
        'slack' => [
            
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'error'),
        ],
        
        'critical' => [
            'driver' => 'stack',
            'channels' => ['daily','slackCritical'],
            'ignore_exceptions' => false,
        ], 
        
        'slackCritical' => [
            'driver' => 'slack',
            'url' => env('CRITICAL_LOG_SLACK_WEBHOOK_URL'),
            'username' => 'SimotelProxyCritical',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
            'replace_placeholders' => true,
        ],

    ],
];
