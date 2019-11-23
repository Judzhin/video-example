<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App;

return [
    'smtp_options' => [
        'name' => 'mailhog',
        'host' => 'host.docker.internal',
        'port' => 1025,
    ]
];
