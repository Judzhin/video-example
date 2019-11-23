<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video;

use App\ConfigProvider;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PreferenceFactory
 * @package App\Service\Video
 */
class PreferenceFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|void
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config')[ConfigProvider::class];

        return new Preference(
            $config['storage'],
            $config['target'],
            self::createSize($config['thumbnail']),
            self::createSizes($config['videos']),
            self::createFormats($config['formats'])
        );
    }

    /**
     * @param array $options
     * @return \Traversable
     */
    private static function createSizes(array $options): \Traversable
    {
        /** @var array $option */
        foreach ($options as $option) {
            yield self::createSize($option);
        }
    }

    /**
     * @param array $option
     * @return Size
     */
    private static function createSize(array $option): Size
    {
        return new Size($option[0], $option[1]);
    }

    /**
     * @param array $names
     * @return \Traversable
     */
    private static function createFormats(array $names): \Traversable
    {
        /** @var string $name */
        foreach ($names as $name) {
            yield new Format($name);
        }
    }
}
