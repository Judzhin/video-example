<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Handler;

use App\Handler\ConfirmHandler;
use App\Handler\ConfirmHandlerFactory;
use AppTest\ContainerTestTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\EventManager\EventManager;

/**
 * Class ConfirmHandlerFactoryTest
 * @package AppTest\Handler
 */
class ConfirmHandlerFactoryTest extends TestCase
{
    use ContainerTestTrait;

    public function testInstance()
    {
        /** @var ObjectProphecy $container */
        $container = $this->getContainer();

        /** @var ConfirmHandlerFactory $factory */
        $factory = new ConfirmHandlerFactory;
        $this->assertInstanceOf(ConfirmHandlerFactory::class, $factory);

        $container
            ->get(EntityManager::class)
            ->willReturn($this->prophesize(EntityManagerInterface::class));

        $container
            ->get(EventManager::class)
            ->willReturn($this->prophesize(EventManager::class));

        /** @var ConfirmHandler $handler */
        $handler = $factory($container->reveal(), ConfirmHandler::class);

        $this->assertInstanceOf(ConfirmHandler::class, $handler);
    }
}
