<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Command\Kafka;

use Kafka\Producer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Json\Encoder;

/**
 * Class ProducerCommand
 * @package App\Command\Kafka
 */
class ProducerCommand extends Command
{
    /** @const NAME */
    public const NAME = 'demo:kafka:producer';

    /** @var Producer */
    protected $producer;

    /**
     * ProducerCommand constructor.
     * @param Producer $producer
     */
    public function __construct(Producer $producer)
    {
        parent::__construct();
        $this->producer = $producer;
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName(self::NAME)// ->setDescription('Some Description')
            ->addArgument('user_id', InputArgument::REQUIRED)
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
        $output->writeln('<comment>Produce message</comment>');

        // for ($i = 0; $i < 100; $i++) {
        $this->producer->send([
            [
                'topic' => 'notifications',
                'value' => Encoder::encode([
                    'type' => 'notification',
                    'user_id' => $input->getArgument('user_id'),
                    'message' => 'Hello!'
                ]),
                'key' => '',
            ],
        ]);
        // }

        $output->writeln('<info>Done!</info>');
    }
}
