<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Middleware;

use MSBios\Exception\DomainException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\PhpEnvironment\Response;

/**
 * Class DomainExceptionMiddleware
 * @package App\Middleware
 */
class DomainExceptionMiddleware implements MiddlewareInterface
{
    /**
     * @inheritdoc
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (DomainException $exception) {
            return new JsonResponse([
                'error' => 'domain_data',
                'error_description' => 'Value does not match certain valid domain data',
                'message' => $exception->getMessage()
            ], Response::STATUS_CODE_400);
        }
    }
}
