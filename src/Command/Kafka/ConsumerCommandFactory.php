<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Command\Kafka;

use Interop\Container\ContainerInterface;
use Kafka\Consumer;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ConsumerCommandFactory
 * @package App\Command\Kafka
 */
class ConsumerCommandFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ConsumerCommand|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ConsumerCommand(
            $container->get(Consumer::class)
        );
    }
}
