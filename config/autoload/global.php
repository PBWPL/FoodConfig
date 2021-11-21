<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\RemoteAddr;
use Laminas\Cache\Storage\Adapter\Filesystem;
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    // Connect database global
    'db' => [
        'driver' => 'Pdo_Mysql',
        'database' => '', // edit
        'username' => '', // edit
        'password' => '', // edit
        'hostname' => '', // edit
        'charset' => 'utf8'
    ],

    // Connect database doctrine
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => '', // edit
                    'user'     => '', // edit
                    'password' => '', // edit
                    'dbname'   => '', // edit
                ]
            ],
        ],
    ],

    // Session config
    'session_config' => [
        'cookie_lifetime'     => 60*60*1, // expire (1 hour)
        'gc_maxlifetime'      => 60*60*24*30, // store session data (1 month)
    ],

    // Session manager config
    'session_manager' => [
        'validators' => [ // validators (security)
            RemoteAddr::class,
        ]
    ],

    // Session storage config
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],

    // Cache config
    'caches' => [
        'FilesystemCache' => [
            'adapter' => [
                'name'    => Filesystem::class,
                'options' => [
                    'cache_dir' => './data/cache', // store cached
                    'ttl' => 60*60*1 // store cached data (1 hour)
                ],
            ],
            'plugins' => [
                [
                    'name' => 'serializer',
                    'options' => [
                    ],
                ],
            ],
        ],
    ],
];
