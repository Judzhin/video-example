<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Command;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FixtureCommand
 * @package App\Command
 */
class FixtureCommand extends Command implements ObjectManagerAwareInterface
{
    use ProvidesObjectManager;

    /** @var string  */
    public const NAME = 'fixtures:load';

    /** @var string */
    private $path;

    /**
     * FixtureCommand constructor.
     * @param ObjectManager $objectManager
     * @param string $path
     */
    public function __construct(ObjectManager $objectManager, string $path = 'src/Data/Fixture')
    {
        parent::__construct();
        $this->setObjectManager($objectManager);
        $this->path = $path;
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Load fixtures')
        ;
    }

    /**
     * @inheritdoc
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Loading fixtures</comment>');

        /** @var Loader $loader */
        $loader = new Loader;
        $loader->loadFromDirectory($this->path);

        /** @var ORMExecutor $executor */
        $executor = new ORMExecutor($this->getObjectManager(), new ORMPurger);

        $executor->setLogger(function ($message) use ($output) {
            $output->writeln($message);
        });

        $executor->execute($loader->getFixtures());

        $output->writeln('<info>Done!</info>');
    }
}
