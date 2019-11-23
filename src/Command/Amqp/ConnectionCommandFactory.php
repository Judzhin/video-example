<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Command\Amqp;

use Interop\Container\ContainerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ConnectionCommandFactory
 * @package App\Command\Amqp
 */
class ConnectionCommandFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            $container->get(AMQPStreamConnection::class)
        );
    }
}
