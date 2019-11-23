<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\AMQP;

return [
    Module::class => [
        'host' => 'video-rabbitmq',
        'port' => 5672,
        'user' => 'rabbit',
        'password' => 'rabbit',
        // 'vhost' => '/'
    ]
];