<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest;

use App\CryptKeyFactory;
use App\EventManagerFactory;
use Interop\Container\ContainerInterface;
use League\OAuth2\Server\CryptKey;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\EventManager\EventManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class EventManagerFactoryTest
 * @package AppTest
 */
class EventManagerFactoryTest extends TestCase
{
    public function testInstance()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);

        $container
            ->get('config')
            ->willReturn(['events' => [
                'target' => [
                    [
                        'listener' => 'Member',
                        'method' => '__invoke',
                    ]
                ]
            ]]);

        /** @var FactoryInterface $factory */
        $factory = new EventManagerFactory;

        /** @var CryptKey $cryptKey */
        $cryptKey = $factory($container->reveal(), EventManager::class);
        $this->assertInstanceOf(EventManager::class, $cryptKey);
    }
}
