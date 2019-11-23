<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Handler;

use App\Handler\HomePageHandler;
use App\Handler\HomePageHandlerFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class HomePageHandlerFactoryTest
 * @package AppTest\Handler
 */
class HomePageHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    protected $container;

    /**
     * @constructor
     */
    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);

        /** @var RouterInterface $router */
        $router = $this->prophesize(RouterInterface::class);
        $this->container
            ->get(RouterInterface::class)
            ->willReturn($router);
    }

    public function testFactoryWithoutTemplate()
    {
        /** @var HomePageHandlerFactory $factory */
        $factory = new HomePageHandlerFactory;

        $this->container
            ->has(TemplateRendererInterface::class)
            ->willReturn(false);

        $this->assertInstanceOf(HomePageHandlerFactory::class, $factory);

        /** @var HomePageHandler $homePage */
        $homePage = $factory($this->container->reveal());

        $this->assertInstanceOf(HomePageHandler::class, $homePage);
    }

    public function testFactoryWithTemplate()
    {
        $this->container
            ->has(TemplateRendererInterface::class)
            ->willReturn(true);

        $this->container
            ->get(TemplateRendererInterface::class)
            ->willReturn($this->prophesize(TemplateRendererInterface::class));

        /** @var HomePageHandlerFactory $factory */
        $factory = new HomePageHandlerFactory;

        /** @var HomePageHandler $homePage */
        $homePage = $factory($this->container->reveal());

        $this->assertInstanceOf(HomePageHandler::class, $homePage);
    }
}
