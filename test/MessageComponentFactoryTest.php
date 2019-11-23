<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest;

use App\MessageComponent;
use App\MessageComponentFactory;
use Interop\Container\ContainerInterface;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Ratchet\MessageComponentInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\ServiceManager\PluginManagerInterface;

/**
 * Class MessageComponentFactoryTest
 * @package AppTest
 */
class MessageComponentFactoryTest extends TestCase
{
    /**
     *
     */
    public function testInstance()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);

        $container
            ->get(CryptKey::class)
            ->willReturn($this->prophesize(CryptKey::class));

        $container
            ->get(AccessTokenRepositoryInterface::class)
            ->willReturn($this->prophesize(AccessTokenRepositoryInterface::class));

        /** @var MessageComponentFactory $factory */
        $factory = new MessageComponentFactory;

        /** @var MessageComponent $messageComponent */
        $messageComponent = $factory($container->reveal(), MessageComponent::class);

        $this->assertInstanceOf(MessageComponentInterface::class, $messageComponent);

    }
}
