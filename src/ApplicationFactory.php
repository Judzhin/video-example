<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App;

use Doctrine\DBAL\Connection;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Doctrine\ORM\Tools;
use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ApplicationFactory
 * @package App
 */
class ApplicationFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Application
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Application
    {
        /** @var array $config */
        $config = $container->get('config')['cli'];

        /** @var Application $cli */
        $cli = new Application('Application console');

        /** @var \Doctrine\Common\Persistence\ObjectManager|\Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.entity_manager.orm_default');

        /** @var Connection $connection */
        $connection = $entityManager->getConnection();

        /** @var Configuration $configuration */
        $configuration = new Configuration($connection);
        $configuration->setMigrationsDirectory('src/Data/Migration');
        $configuration->setMigrationsNamespace('Api\Data\Migration');

        /** @var \Symfony\Component\Console\Helper\HelperSet $helperSet */
        $helperSet = $cli->getHelperSet();
        $helperSet->set(new Tools\Console\Helper\EntityManagerHelper($entityManager), 'em');
        $helperSet->set(new ConfigurationHelper($connection, $configuration), 'configuration');

        Tools\Console\ConsoleRunner::addCommands($cli);
        \Doctrine\Migrations\Tools\Console\ConsoleRunner::addCommands($cli);

        $cli->setCommandLoader(new ContainerCommandLoader($container, $config['commands'] ?? []));
        return $cli;
    }
}
