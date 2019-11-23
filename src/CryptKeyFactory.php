<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App;

use Interop\Container\ContainerInterface;
use League\OAuth2\Server\CryptKey;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class CryptKeyFactory
 * @package App
 */
class CryptKeyFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return CryptKey|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config')['authentication'];
        return new CryptKey($config['public_key']);
    }
}
