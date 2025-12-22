<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // Shard connections - these will be dynamically registered
    ],

    'sharding' => [
        'strategy' => 'hash', // hash, range, directory
        'shard_key' => 'user_id',
        
        'shards' => [
            'shard1' => [
                'driver' => 'mysql',
                'host' => env('DB_SHARD1_HOST', '127.0.0.1'),
                'port' => env('DB_SHARD1_PORT', '3307'),
                'database' => env('DB_SHARD1_DATABASE', 'shard1'),
                'username' => env('DB_SHARD1_USERNAME', 'root'),
                'password' => env('DB_SHARD1_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
            ],

            'shard2' => [
                'driver' => 'mysql',
                'host' => env('DB_SHARD2_HOST', '127.0.0.1'),
                'port' => env('DB_SHARD2_PORT', '3308'),
                'database' => env('DB_SHARD2_DATABASE', 'shard2'),
                'username' => env('DB_SHARD2_USERNAME', 'root'),
                'password' => env('DB_SHARD2_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
            ],

            'shard3' => [
                'driver' => 'mysql',
                'host' => env('DB_SHARD3_HOST', '127.0.0.1'),
                'port' => env('DB_SHARD3_PORT', '3309'),
                'database' => env('DB_SHARD3_DATABASE', 'shard3'),
                'username' => env('DB_SHARD3_USERNAME', 'root'),
                'password' => env('DB_SHARD3_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
            ],

            'shard4' => [
                'driver' => 'mysql',
                'host' => env('DB_SHARD4_HOST', '127.0.0.1'),
                'port' => env('DB_SHARD4_PORT', '3310'),
                'database' => env('DB_SHARD4_DATABASE', 'shard4'),
                'username' => env('DB_SHARD4_USERNAME', 'root'),
                'password' => env('DB_SHARD4_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
            ],
        ],

        // For range-based sharding
        'ranges' => [
            'shard1' => ['min' => 0, 'max' => 999],
            'shard2' => ['min' => 1000, 'max' => 1999],
            'shard3' => ['min' => 2000, 'max' => 2999],
            'shard4' => ['min' => 3000, 'max' => 3999],
        ],

        // For directory-based sharding (specific mapping)
        'directory' => [
            'special_user_1' => 'shard1',
            'special_user_2' => 'shard1',
            'premium_customer' => 'shard2',
            // ... other specific mappings
        ],
    ],

    'migrations' => 'migrations',

    'redis' => [
        // ... redis configuration
    ],
];