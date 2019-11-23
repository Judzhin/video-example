<?php

declare(strict_types=1);

return [
    'log' => [
        'writers' => [
            [
                'name' => 'stream',
                'options' => [
                    'stream' => "data/logs/info.log",
                    'filters' => [
                        'priority' => [
                            'name' => 'priority',
                            'options' => [
                                'priority' => \Zend\Log\Logger::INFO
                            ],
                        ],
                    ],
                    'formatter' => [
                        'name' => 'simple',
                        'options' => [
                            'dateTimeFormat' => 'Y-m-d H:i:s'
                        ],
                    ],
                ],
            ], [
                'name' => 'stream',
                'options' => [
                    'stream' => "data/logs/errors.log",
                    'filters' => [
                        'priority' => [
                            'name' => 'priority',
                            'options' => [
                                'priority' => \Zend\Log\Logger::ERR
                            ],
                        ],
                    ],
                    'formatter' => [
                        'name' => 'simple',
                        'options' => [
                            'dateTimeFormat' => 'Y-m-d H:i:s'
                        ],
                    ],
                ],
            ],
        ]
    ]
];
