<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Handler;

use Interop\Container\ContainerInterface;
use MSBios\Doctrine\Factory\ObjectableFactory;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;

/**
 * Class ConfirmHandlerFactory
 * @package App\Handler
 */
class ConfirmHandlerFactory extends ObjectableFactory
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ConfirmHandler|mixed|object
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ConfirmHandler $instance */
        $instance = parent::__invoke($container, $requestedName, $options);

        /** @var EventManagerInterface $eventManager */
        $instance->setEventManager(
            $container->get(EventManager::class)
        );

        return $instance;
    }
}
