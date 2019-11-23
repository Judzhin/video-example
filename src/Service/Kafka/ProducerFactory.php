<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Kafka;

use Interop\Container\ContainerInterface;
use Kafka\ProducerConfig;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ProducerFactory
 * @package App\Service\Kafka
 */
class ProducerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return \Kafka\Producer|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config')['kafka'];

        /** @var ProducerConfig $producerConfig */
        $producerConfig = \Kafka\ProducerConfig::getInstance();
        $producerConfig->setMetadataRefreshIntervalMs(10000);
        $producerConfig->setMetadataBrokerList($config['broker_list']);
        $producerConfig->setBrokerVersion('1.1.0');
        $producerConfig->setRequiredAck(1);
        $producerConfig->setIsAsyn(false);
        // $producerConfig->setProduceInterval(500);

        return new \Kafka\Producer;
    }
}
