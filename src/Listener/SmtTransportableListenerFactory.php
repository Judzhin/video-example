<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Listener;

use Interop\Container\ContainerInterface;
use Zend\Mail\Transport\Smtp;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class SmtTransportableListenerFactory
 * @package App\Listener
 */
class SmtTransportableListenerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            $container->get(Smtp::class)
        );
    }
}
