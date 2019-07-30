<?php
$DATABASE_URL=parse_url(‘DATABASE_URL’);
return [
    'default' => 'pgsql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => $DATABASE_URL['host'],
            'port' => $DATABASE_URL['port'],
            'database' => ltrim($DATABASE_URL['path'], "/"),
            'username' => $DATABASE_URL['user'],
            'password' => $DATABASE_URL['pass'],
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
    ]
];