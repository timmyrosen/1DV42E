<?php

$config = [
    'path' => '/1DV42E',
    'host' => 'localhost',
    'logs' => [
        'error' => 'logs/error.log'
    ],
    'database' => [
        'host'    => 'localhost',
        'user'    => 'root',
        'pass'    => '',
        'db'      => 'rabattrea',
        'driver'  => 'mysql',
        'charset' => 'utf8'
    ],
    'autoloading' => [
        'path'      => 'autoloader.cache',
        'resources' => ['app'],
        'exclude'   => ['']
    ]
];