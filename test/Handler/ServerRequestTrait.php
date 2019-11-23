<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;
use Zend\Http\PhpEnvironment\Request;
use Zend\Json\Encoder;
use Zend\Stdlib\ArrayUtils;

/**
 * Trait ServerRequestTrait
 * @package AppTest\Handler
 */
trait ServerRequestTrait
{
    /**
     * @param array $params
     * @return ServerRequestInterface
     */
    protected function request(array $params = []): ServerRequestInterface
    {
        /** @var ObjectProphecy|StreamInterface|Stream $body */
        $request = $this->prophesize(ServerRequestInterface::class);

        $request
            ->getParsedBody()
            ->willReturn($params);

        return $request->reveal();
    }
}