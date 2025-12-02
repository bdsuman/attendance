<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default documentation
    |--------------------------------------------------------------------------
    */
    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => env('APP_NAME', 'Laravel') . ' API Documentation',
            ],

            // Routes to access documentation and docs json
            'routes' => [
                'api' => 'api/documentation',
                'docs' => 'docs',
            ],

            'paths' => [
                // where to look for annotations
                'annotations' => [
                    base_path('app'),
                ],

                // where to save generated json
                'docs_json' => storage_path('api-docs/api-docs.json'),

                // where to save generated swagger ui files
                'docs' => public_path('api-docs'),
            ],
        ],
    ],

    'defaults' => [
        'ui' => [
            'display' => true,
        ],

        'generate_always' => false,
    ],
];
