<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest;

use App\IoServerFactory;
use App\MessageComponent;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;

/**
 * Class IoServerFactoryTest
 * @package AppTest
 */
class IoServerFactoryTest extends TestCase
{
    /**
     *
     */
    public function testInstance()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(MessageComponent::class)
            ->willReturn($this->prophesize(MessageComponentInterface::class));

        /** @var IoServerFactory $factory */
        $factory = new IoServerFactory;

        /** @var IoServer $server */
        $server = $factory($container->reveal(), IoServer::class);

        $this->assertInstanceOf(IoServer::class, $server);
    }
}
