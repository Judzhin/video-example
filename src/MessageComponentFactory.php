<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App;

use Interop\Container\ContainerInterface;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class MessageComponentFactory
 * @package App
 */
class MessageComponentFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return MessageComponent|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MessageComponent(
            $container->get(CryptKey::class),
            $container->get(AccessTokenRepositoryInterface::class)
        );
    }
}
