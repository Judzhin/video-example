<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class HomePageHandler
 * @package App\Handler
 */
class HomePageHandler implements RequestHandlerInterface
{
    /** @var Router\RouterInterface */
    private $router;

    /** @var null|TemplateRendererInterface */
    private $template;

    /**
     * HomePageHandler constructor.
     * @param Router\RouterInterface $router
     * @param null|TemplateRendererInterface $template
     */
    public function __construct(Router\RouterInterface $router, ?TemplateRendererInterface $template = null)
    {
        $this->router = $router;
        $this->template = $template;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        return new JsonResponse([
            'name' => 'Application API',
            'version' => '1.0',
        ]);

        // if ($this->template === null) {
        //     return new JsonResponse([
        //         'welcome' => 'Congratulations! You have installed the zend-expressive skeleton application.',
        //         'docsUrl' => 'https://docs.zendframework.com/zend-expressive/',
        //     ]);
        // }
        //
        // return new HtmlResponse($this->template->render('app::home-page', [
        //     // ...
        // ]));
    }
}
