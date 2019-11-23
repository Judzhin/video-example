<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Service\Video;

use App\ConfigProvider;
use App\Service\Video\Preference;
use App\Service\Video\PreferenceFactory;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PreferenceFactoryTest
 * @package AppTest\Service\Video
 */
class PreferenceFactoryTest extends TestCase
{
    public function testInstance()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get('config')
            ->willReturn([
                ConfigProvider::class => [
                    'storage' => 'path/to/storage',
                    'target' => 'path/to/target',
                    'thumbnail' => [0, 0],
                    'videos' => [
                        [0, 0],
                    ],
                    'formats' => [
                        'webm',
                    ]
                ]
            ]);

        /** @var FactoryInterface $factory */
        $factory = new PreferenceFactory;

        /** @var Preference $preference */
        $preference = $factory($container->reveal(), Preference::class);

        $this->assertInstanceOf(Preference::class, $preference);
    }
}