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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Json\Decoder;

/**
 * Class ConsumerCommand
 * @package App\Command\Amqp
 */
class ConsumerCommand extends AbstractConnectionCommand
{
    /** @const NAME */
    public const NAME = 'demo:amqp:consumer';

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

        /** @var AMQPStreamConnection $connection */
        $connection = $this->getAMQPStreamConnection();

        /** @var AMQPChannel $channel */
        $channel = $this->getAMQPStreamConnection()->channel();

        AMQPHelper::initNotification($channel);
        AMQPHelper::registerShutdown($connection, $channel);

        /** @var string $consumerTag */
        $consumerTag = 'consumer_' . getmygid();

        /** @var  $onCallback */
        $onCallback = function (AMQPMessage $message) use ($output) {
            $output->writeln(print_r(
                Decoder::decode($message->body),
                true
            ));

            /** @var AMQPChannel $channel */
            $channel = $message->delivery_info['channel'];
            $channel->basic_ack($message->delivery_info['delivery_tag']);
        };

        $channel->basic_consume(
            AMQPHelper::QUEUE_NOTIFICATIONS,
            $consumerTag,
            false,
            false,
            false,
            false,
            $onCallback
        );

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $output->writeln('<info>Done!</info>');
    }
}
