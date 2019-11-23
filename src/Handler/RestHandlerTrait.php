<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Handler;

use App\Exception\MethodNotAllowedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\PhpEnvironment\Response;

/**
 * Trait RestHandlerTrait
 * @package App\Handler
 */
trait RestHandlerTrait
{
    /**
     * @inheritdoc
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (! method_exists($this, $method = strtolower($request->getMethod()))) {
            throw MethodNotAllowedException::create(sprintf(
                'Method %s is not implemented for the requested resource.',
                strtoupper($method)
            ));
        }

        return $this->$method($request);
    }

    // /**
    //  * @param ServerRequestInterface $request
    //  * @return ResponseInterface
    //  */
    // public function get(ServerRequestInterface $request): ResponseInterface
    // {
    //     return new JsonResponse([], Response::STATUS_CODE_405);
    // }

    // /**
    //  * @param ServerRequestInterface $request
    //  * @return ResponseInterface
    //  */
    // public function create(ServerRequestInterface $request): ResponseInterface
    // {
    //     return new JsonResponse([], Response::STATUS_CODE_405);
    // }

    // /**
    //  * @param ServerRequestInterface $request
    //  * @return ResponseInterface
    //  */
    // public function post(ServerRequestInterface $request): ResponseInterface
    // {
    //     return new JsonResponse([], Response::STATUS_CODE_405);
    // }

    // /**
    //  * @param ServerRequestInterface $request
    //  * @return ResponseInterface
    //  */
    // public function put(ServerRequestInterface $request): ResponseInterface
    // {
    //     return new JsonResponse([], Response::STATUS_CODE_405);
    // }

    // /**
    //  * @param ServerRequestInterface $request
    //  * @return ResponseInterface
    //  */
    // public function delete(ServerRequestInterface $request): ResponseInterface
    // {
    //     return new JsonResponse([], Response::STATUS_CODE_405);
    // }
}
