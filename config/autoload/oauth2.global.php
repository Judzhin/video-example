<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App;

use League\OAuth2\Server\Grant;
use League\OAuth2\Server\Repositories;

return [
    'dependencies' => [
        'abstract_factories' => [
            Repository\RepositoryAbstractFactory::class
        ],
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [

            // Repositories
            Repositories\AccessTokenRepositoryInterface::class =>
                Repository\AccessTokenRepository::class,
            Repositories\AuthCodeRepositoryInterface::class =>
                Repository\AuthCodeRepository::class,
            Repositories\ClientRepositoryInterface::class =>
                Repository\ClientRepository::class,
            Repositories\RefreshTokenRepositoryInterface::class =>
                Repository\RefreshTokenRepository::class,
            Repositories\ScopeRepositoryInterface::class =>
                Repository\ScopeRepository::class,
            Repositories\UserRepositoryInterface::class =>
                Repository\UserRepository::class,

            \Zend\Expressive\Authentication\AuthenticationInterface::class =>
                \Zend\Expressive\Authentication\OAuth2\OAuth2Adapter::class,
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories' => [
            Repository\AccessTokenRepository::class =>
                Repository\RepositoryAbstractFactory::class
        ],
    ],

    'authentication' => [
        'private_key' => __DIR__ . '/../../data/oauth/private.key', // getcwd() . '/data/oauth/private.key',
        'public_key' => __DIR__ . '/../../data/oauth/public.key', // getcwd() . '/data/oauth/public.key',
        'encryption_key' => require __DIR__ . '/../../data/oauth/encryption.key', // getcwd() . '/data/oauth/encryption.key',
        'access_token_expire' => 'P1D',
        'refresh_token_expire' => 'P1M',
        'auth_code_expire' => 'PT10M',

        // 'pdo' => [
        //     'dsn' => '',
        //     'username' => '',
        //     'password' => ''
        // ],

        // Set value to null to disable a grant
        'grants' => [
            Grant\ClientCredentialsGrant::class =>
                Grant\ClientCredentialsGrant::class,
            Grant\PasswordGrant::class =>
                Grant\PasswordGrant::class,
            Grant\AuthCodeGrant::class =>
                Grant\AuthCodeGrant::class,
            Grant\ImplicitGrant::class =>
                Grant\ImplicitGrant::class,
            Grant\RefreshTokenGrant::class =>
                Grant\RefreshTokenGrant::class
        ],
    ],

];
