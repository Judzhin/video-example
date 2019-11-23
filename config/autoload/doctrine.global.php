<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => null,
                'params' => [
                    // 'url' => 'pgsql://user:pass@host:port/database?charset=utf8',
                    'host' => null,
                    'port' => null,
                    'user' => null,
                    'password' => null,
                    'dbname' => null,
                    'charset' => 'UTF8',
                    'driverOptions' => [
                        // \PDO::PGSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    ]
                ],
            ],
        ],
    ],
];
