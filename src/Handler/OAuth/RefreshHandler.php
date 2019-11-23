<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Handler\OAuth;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\PhpEnvironment\Response;

/**
 * Class RefreshHandler
 * @package App\Handler\OAuth
 */
class RefreshHandler implements RequestHandlerInterface
{
    /**
     * @inheritdoc
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([
        ], Response::STATUS_CODE_201);
    }
}
