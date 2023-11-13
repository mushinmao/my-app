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

    'shortener' => [
        'class' => \App\UrlConverter\UrlConverter::class,
        'arguments' => [
            '@shortener_coder',
            '@shortener_randomizer',
            '@shortener_validator'
        ],
    ],

    'shortener_randomizer' => [
        'class' => \App\Randomizer\Randomizer::class,
        'arguments' => ['$randomizer.chars'],
        'calls' => [
            [
                'method' => 'setLength',
                'arguments' => ['$randomizer.length'],
            ],
        ],
    ],

    'shortener_coder' => [
        'class' => \App\Coder\StringCoder::class,
    ],

    'shortener_validator' => [
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