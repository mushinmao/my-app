<?php

/**
 * Example:
 *
 * 'service_name' => [
 *      'class' => ServiceClass::class,
 *      'arguments' => (array) [...args],
 *      'calls' => [
 *          [
 *              'method' => (string) methodName,
 *              'args' => (array) [...args]
 *          ],
 *      ],
 *      'composition' => [
 *          (string) parent_class => (string) method
 *       ],
 *      'compiler' => Closure // anonymous function,
 *      'tags' => (array) [...tags]
 * ],
 */

return [

    'json' => [
        'class' => \Josantonius\Json\Json::class,
        'arguments' => [__DIR__.'/../data/database.json'],
    ],

    'url_converter' => [
        'class' => \App\UrlConverter\UrlConverter::class,
        'arguments' => [
            '@coder',
            '@randomizer',
            '@url_validator'
        ],
    ],

    'randomizer' => [
        'class' => \App\Randomizer\Randomizer::class,
        'arguments' => ['$randomizer.chars'],
        'calls' => [
            [
                'method' => 'setLength',
                'arguments' => ['$randomizer.length'],
            ],
        ],
    ],

    'coder' => [
        'class' => \App\Coder\StringCoder::class,
    ],

    'url_validator' => [
        'class' => \App\UrlValidator\UrlValidator::class,
    ],

    'db_manager' => [
        'class' => \Illuminate\Database\Capsule\Manager::class,
        'calls' => [
            [
                'method' => 'addConnection',
                'arguments' => ['$eloquent_config'],
            ],
            [
                'method' => 'bootEloquent',
            ],
        ]
    ],
];