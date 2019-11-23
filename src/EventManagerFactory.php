<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App;

use Interop\Container\ContainerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\LazyListener;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class EventManagerFactory
 * @package App
 */
class EventManagerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|EventManagerInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EventManagerInterface $eventManager */
        $eventManager = new EventManager;

        /** @var array $config */
        $config = $container->get('config')['events'];

        /**
         * @var string $eventName
         * @var array $events
         */
        foreach ($config as $eventName => $events) {

            /** @var array $event */
            foreach ($events as $event) {
                /** @var LazyListener $lazyListener */
                $lazyListener = new LazyListener($event, $container);
                $eventManager->attach($eventName, $lazyListener);
            }
        }

        return $eventManager;
    }
}
