<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Listener\Author;

use App\Service\Video\FormatDetector\FFProbeFormatDetector;
use App\Service\Video\Preference;
use Interop\Container\ContainerInterface;
use Kafka\Producer;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class VideoListenerFactory
 * @package App\Listener\Author
 */
class VideoListenerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return VideoListener|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new VideoListener(
            // $container->get(Producer::class)
            $container->get(AMQPStreamConnection::class),
            $container->get(Preference::class),
            $container->get(FFProbeFormatDetector::class)
        );
    }
}
