<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Ratchet\Server\IoServer;

(function () {
    /** @var ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';

    /** @var IoServer $server */
    $server = $container->get(IoServer::class);
    $server->run();
})();