<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Kafka;

use Interop\Container\ContainerInterface;
use Kafka\ConsumerConfig;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ConsumerFactory
 * @package App\Service\Kafka
 */
class ConsumerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return \Kafka\Consumer|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config')['kafka'];

        /** @var ConsumerConfig $consumerConfig */
        $consumerConfig = \Kafka\ConsumerConfig::getInstance();
        $consumerConfig->setMetadataRefreshIntervalMs(10000);
        $consumerConfig->setMetadataBrokerList($config['broker_list']);
        $consumerConfig->setBrokerVersion('1.1.0');
        $consumerConfig->setGroupId('demo');
        $consumerConfig->setTopics(['notifications']);

        return new \Kafka\Consumer;
    }
}
