<?php

declare(strict_types=1);

namespace AppTest\Handler;

use App\Handler\HomePageHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Http\PhpEnvironment\Response;

/**
 * Class HomePageHandlerTest
 * @package AppTest\Handler
 */
class HomePageHandlerTest extends TestCase
{
    /**
     *
     */
    public function testSuccess(): void
    {
        /** @var RequestHandlerInterface $handler */
        $handler = new HomePageHandler(
            $this->prophesize(RouterInterface::class)->reveal(), null
        );

        /** @var JsonResponse $response */
        $response = $handler->handle(
            $this->prophesize(ServerRequestInterface::class)->reveal()
        );

        $this->assertEquals(Response::STATUS_CODE_200, $response->getStatusCode());
        $this->assertJson($content = $response->getBody()->getContents());
        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
