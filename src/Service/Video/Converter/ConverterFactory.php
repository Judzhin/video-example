<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video\Converter;

use App\ConfigProvider;
use App\Service\Video\Preference;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ConverterFactory
 * @package App\Service\Video\Converter
 */
class ConverterFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Converter|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config')[ConfigProvider::class];

        /** @var Preference $preference */
        $preference = $container->get(Preference::class);

        /** @var Converter $converter */
        $converter = new Converter;

        foreach ($config['converter_resolvers'] as $resolver => $priority) {
            $converter->attach(new $resolver($preference->getTarget()), $priority);
        }

        return $converter;
    }
}
