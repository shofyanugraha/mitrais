<?php
$DATABASE_URL=parse_url('postgres://vglpbuiccsradq:0532c0669d41295d874102858a5d4ed9376bb3dae52be3fb92333926015ac288@ec2-174-129-226-232.compute-1.amazonaws.com:5432/d4jng82coebuve');
return [
    'default' => 'pgsql',
    'migrations' => 'migrations',
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