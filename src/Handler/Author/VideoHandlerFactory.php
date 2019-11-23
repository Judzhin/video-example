<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Handler\Author;

use App\Service\Video\Converter\Converter;
use App\Service\Video\FormatDetector\FFProbeFormatDetector;
use App\Service\Video\Preference;
use App\Service\Video\Thumbnailer\Thumbnailer;
use Interop\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\EventManager\EventManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class VideoHandlerFactory
 * @package App\Handler\Author
 */
class VideoHandlerFactory implements FactoryInterface
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
        /** @var RequestHandlerInterface $handler */
        $handler = new VideoHandler(
            $container->get('doctrine.entity_manager.orm_default'),
            $container->get(Preference::class)
        );

        $handler->setEventManager($container->get(EventManager::class));

        return $handler;
    }
}
