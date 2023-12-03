<?php
return [
    'randomizer' => [
        'chars' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'length' => 8,
    ],

    'database' => [
        'driver' => 'pdo_mysql',
        'host' => 'db_mysql',
        'port' => '3306',
        'name' => 'db',
        'username' => 'mushin',
        'password' => '123',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'isDevMode' => false,
    ],
];