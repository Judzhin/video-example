<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest;

use App\MessageComponent;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Ratchet\ConnectionInterface;
use Zend\Json\Encoder;

/**
 * Class MessageComponentTest
 * @package AppTest
 */
class MessageComponentTest extends TestCase
{
    /** @var MessageComponent */
    protected $messageComponent;

    /** @var ConnectionInterface|ObjectProphecy */
    protected $connection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->messageComponent = new MessageComponent(
            $this->prophesize(CryptKey::class)->reveal(),
            $this->prophesize(AccessTokenRepositoryInterface::class)->reveal()
        );

        $this->connection = $this->prophesize(ConnectionInterface::class);
    }

    public function testCallOnOpenMethod()
    {
        $this->messageComponent->onOpen(
            $this->connection->reveal()
        );

        $this->assertTrue(true);
    }

    public function testCallOnMessage()
    {
        $this->messageComponent->onOpen(
            $this->prophesize(ConnectionInterface::class)->reveal()
        );

        $this->messageComponent->onMessage(
            $this->connection->reveal(),
            Encoder::encode([])
        );

        $this->assertTrue(true);
    }

    public function testCallOnCloseMethod()
    {
        $this->messageComponent->onClose(
            $this->connection->reveal()
        );
        $this->assertTrue(true);
    }

    public function testCallOnErrorMethod()
    {
        $this->messageComponent->onError(
            $this->connection->reveal(),
            $this->prophesize(\Exception::class)->reveal()
        );
        $this->assertTrue(true);
    }
}