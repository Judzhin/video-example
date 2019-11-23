<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Command\Kafka;

use Kafka\Consumer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsumerCommand
 * @package App\Command\Kafka
 */
class ConsumerCommand extends Command
{
    /** @const NAME */
    public const NAME = 'demo:kafka:consumer';

    /** @var Consumer */
    protected $consumer;

    /**
     * ConsumerCommand constructor.
     * @param Consumer $consumer
     */
    public function __construct(Consumer $consumer)
    {
        parent::__construct();
        $this->consumer = $consumer;
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            // ->setDescription('Some Description')
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
        $output->writeln('<comment>Consumer message</comment>');

        $this->consumer->start(function ($topic, $part, $message) use ($output) {
            $output->writeln(print_r($message, true));
        });

        $output->writeln('<info>Done!</info>');
    }
}
