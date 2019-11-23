<?php

declare(strict_types=1);

namespace App;

use App\Handler;
use App\Middleware\OAuthAuthorizationMiddleware;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\Authentication\AuthenticationMiddleware;
use Zend\Expressive\Authentication\OAuth2\TokenEndpointHandler;
use Zend\Expressive\MiddlewareFactory;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Http\PhpEnvironment\Request;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {

    /** @var array $authorMiddleware */
    $authorMiddleware = [
        AuthenticationMiddleware::class,
        Handler\AuthorHandler::class
    ];

    $app->get('/author', $authorMiddleware, 'author');
    $app->post('/author', $authorMiddleware, 'author.create');

    /** @var array $authorVideoMiddleware */
    $authorVideoMiddleware = [
        AuthenticationMiddleware::class,
        Handler\Author\VideoHandler::class
    ];

    $app->get('/author/video[/{id}]', $authorVideoMiddleware, 'author.video');
    $app->post('/author/video', $authorVideoMiddleware, 'author.video.create');

    $app->post('/confirm', Handler\ConfirmHandler::class, 'confirm');

    $app->get('/', Handler\HomePageHandler::class, 'home');

    $app->post('/oauth/token', TokenEndpointHandler::class, 'oauth');

    $app->route('/oauth2/authorize', [
        SessionMiddleware::class,
        AuthenticationMiddleware::class,
        // The following middleware is provided by your application (see below):
        OAuthAuthorizationMiddleware::class,
        AuthenticationMiddleware::class,
    ], [Request::METHOD_GET, Request::METHOD_POST]);

    $app->get('/profile', [
        AuthenticationMiddleware::class,
        Handler\ProfileHandler::class
    ], 'profile');

    $app->post('/signup', Handler\SignUpHandler::class, 'signup');
};
