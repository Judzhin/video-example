<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Command\Amqp;

use App\Service\Amqp\AMQPHelper;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Json\Encoder;

/**
 * Class ProducerCommand
 * @package App\Command\Amqp
 */
class ProducerCommand extends AbstractConnectionCommand
{
    /** @const NAME */
    public const NAME = 'demo:amqp:producer';

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName(self::NAME)// ->setDescription('Some Description')
            ->addArgument('user_id', InputArgument::REQUIRED);
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

        /** @var AMQPStreamConnection $connection */
        $connection = $this->getAMQPStreamConnection();

        /** @var AMQPChannel $channel */
        $channel = $this->getAMQPStreamConnection()->channel();

        AMQPHelper::initNotification($channel);
        AMQPHelper::registerShutdown($connection, $channel);

        /** @var AMQPMessage $message */
        $message = new AMQPMessage(Encoder::encode([
            'type' => 'notification',
            'user_id' => $input->getArgument('user_id'),
            'message' => 'Hello!'
        ]), ['content_type' => 'text/plain']);

        $channel->basic_publish($message, AMQPHelper::EXCHANGE_NOTIFICATIONS);
        $channel->close();

        $connection->close();

        $output->writeln('<info>Done!</info>');
    }
}
