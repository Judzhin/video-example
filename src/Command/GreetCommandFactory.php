<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Command;

use Interop\Container\ContainerInterface;
use Zend\Log\Logger;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class GreetCommandFactory
 * @package App\Command
 */
class GreetCommandFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return GreetCommand|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new GreetCommand(
            $container->get(Logger::class)
        );
    }
}
