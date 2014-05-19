<?php

$config = [
    'host'      => 'localhost',
    'path'      => 'other/current/binframework/1DV42E',
    'views'     => 'app/views',
    'tmpViews'  => 'app/tmp/views',
    'logs' => [
        'error' => 'logs/error.log'
    ],
    'database' => [
        'connect' => true,
        'host'    => 'localhost',
        'user'    => 'root',
        'pass'    => 'root',
        'db'      => 'supporting',
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
        'Webbins\Session\Session',
        'Webbins\Cookie\Cookie'
    ]
];
