<?php
return [
    'randomizer' => [
        'chars' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'length' => 8,
    ],

    'eloquent_config' => [
        'driver' => 'mysql',
        'host' => 'db_mysql',
        'port' => '3306',
        'database' => 'db',
        'username' => 'mushin',
        'password' => '123',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],
];