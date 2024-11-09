<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Configuration
    |--------------------------------------------------------------------------
    */
    'openai_api_key' => env('OPENAI_API_KEY'),
    'openai_model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    */
    'supported_locales' => [
        'en',
        'es',
        'fr',
        'de',
        'it',
        'pt',
        'ru',
        'zh',
        'ja',
        'ko',
        // Add more locales as needed
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache_enabled' => true,
    'cache_duration' => 60 * 24, // 24 hours

    /*
    |--------------------------------------------------------------------------
    | Scan Paths
    |--------------------------------------------------------------------------
    */
    'scan_paths' => [
        resource_path('views'),
        app_path(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Translation Memory
    |--------------------------------------------------------------------------
    */
    'translation_memory_enabled' => true,
    'translation_memory_threshold' => 0.9, // 90% similarity threshold
];