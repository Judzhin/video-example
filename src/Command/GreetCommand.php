<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;

/**
 * Class GreetCommand
 * @package App\Command
 * @see https://www.elt.ink/blog/2016-02-07-zend-expressive-console-cli-commands/
 */
class GreetCommand extends Command
{
    /** @var string  */
    public const NAME = 'demo:greet';

    /** @var LoggerInterface|Logger */
    private $logger;

    /**
     * GreetCommand constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Greet someone')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
            ->addOption(
                'yell',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            );
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
        /** @var string $name */
        $name = $input->getArgument('name');
        if ($name) {
            $text = 'Hello ' . $name;
        } else {
            $text = 'Hello';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }

        $output->writeln($text);

        $this->logger->info('GreetCommand triggered', ['name' => $name]);
    }
}
