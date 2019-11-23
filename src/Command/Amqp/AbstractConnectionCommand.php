<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Command\Amqp;

use MSBios\AMQP\AMQPStreamConnectionAwareTrait;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractConnectionCommand
 * @package App\Command\Amqp
 */
abstract class AbstractConnectionCommand extends Command
{
    use AMQPStreamConnectionAwareTrait;

    /**
     * AbstractConnectionCommand constructor.
     * @param AMQPStreamConnection $connection
     */
    public function __construct(AMQPStreamConnection $connection)
    {
        parent::__construct();
        $this->setAMQPStreamConnection($connection);
    }
}
