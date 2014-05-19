<?php

$config = [
    'host'      => 'localhost',
    'path'      => '1DV42E',
    'views'     => 'app/views',
    'tmpViews'  => 'app/tmp/views',
    'logs' => [
        'error' => 'logs/error.log'
    ],
    'database' => [
        'connect' => true,
        'host'    => 'localhost',
        'user'    => 'robin',
        'pass'    => 'robin',
        'db'      => 'robin_rabattrea',
        'driver'  => 'mysql',
        'charset' => 'utf8'
    ],
    'autoloading' => [
        'cacheFiles'    => false,
        'path'          => 'autoloader.cache',
        'resources'     => ['app/'],
        'excludes'      => ['']
    ],
    'namespaces' => [
        'Webbins\Database\DB',
        'Webbins\Sessions\Session',
        'Webbins\Cookies\Cookie'
    ]
];
