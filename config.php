<?php

$config = [
    'host'      => 'localhost',
    'path'      => '/1DV42E',
    'views'     => 'app/views',
    'logs' => [
        'error' => 'logs/error.log'
    ],
    'database' => [
        'host'    => 'localhost',
        'user'    => 'robin',
        'pass'    => 'robin',
        'db'      => 'robin_rabattrea',
        'driver'  => 'mysql',
        'charset' => 'utf8'
    ],
    'autoloading' => [
        'cacheFiles'    => true,
        'path'          => 'autoloader.cache',
        'resources'     => ['app/'],
        'excludes'      => ['']
    ]
];
