<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest;

use App\ApplicationFactory;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Application;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ApplicationFactoryTest
 * @package AppTest
 */
class ApplicationFactoryTest extends TestCase
{
    public function testInstance()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);

        /** @var EntityManagerInterface|ObjectProphecy $entityManager */
        $entityManager = $this->prophesize(EntityManagerInterface::class);

        $entityManager
            ->getConnection()
            ->willReturn($this->prophesize(Connection::class));

        $container
            ->get('doctrine.entity_manager.orm_default')
            ->willReturn($entityManager);

        $container
            ->get('config')
            ->willReturn(['cli' => ['commands' => []]]);

        /** @var FactoryInterface $factory */
        $factory = new ApplicationFactory;

        /** @var Application $cli */
        $cli = $factory($container->reveal(), Application::class);
        $this->assertInstanceOf(Application::class, $cli);
    }
}
