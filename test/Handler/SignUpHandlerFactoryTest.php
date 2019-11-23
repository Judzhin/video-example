<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Handler;

use App\Handler\SignUpHandler;
use App\Handler\SignUpHandlerFactory;
use AppTest\ContainerTestTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\EventManager\EventManager;

/**
 * Class SignUpHandlerFactoryTest
 * @package AppTest\Handler
 */
class SignUpHandlerFactoryTest extends TestCase
{
    use ContainerTestTrait;

    public function testInstance()
    {
        /** @var ObjectProphecy $container */
        $container = $this->getContainer();

        /** @var SignUpHandlerFactory $factory */
        $factory = new SignUpHandlerFactory;
        $this->assertInstanceOf(SignUpHandlerFactory::class, $factory);

        $container
            ->get(EntityManager::class)
            ->willReturn($this->prophesize(EntityManagerInterface::class));

        $container
            ->get(EventManager::class)
            ->willReturn($this->prophesize(EventManager::class));

        /** @var SignUpHandler $handler */
        $handler = $factory($container->reveal(), SignUpHandler::class);

        $this->assertInstanceOf(SignUpHandler::class, $handler);
    }
}
