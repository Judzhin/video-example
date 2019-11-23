<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Command\Kafka;

use Interop\Container\ContainerInterface;
use Kafka\Producer;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ProducerCommandFactory
 * @package App\Command\Kafka
 */
class ProducerCommandFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ProducerCommand|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ProducerCommand(
            $container->get(Producer::class)
        );
    }
}
