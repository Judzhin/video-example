<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video\Thumbnailer;

use App\ConfigProvider;
use App\Service\Video\Preference;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ThumbnailerFactory
 * @package App\Service\Video\Thumbnailer
 */
class ThumbnailerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Thumbnailer|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config')[ConfigProvider::class];

        /** @var Preference $preference */
        $preference = $container->get(Preference::class);

        /** @var Thumbnailer $thumbnailer */
        $thumbnailer = new Thumbnailer;

        foreach ($config['thumbnailer_resolvers'] as $resolver => $priority) {
            $thumbnailer->attach(new $resolver($preference->getTarget()), $priority);
        }

        return $thumbnailer;
    }
}
