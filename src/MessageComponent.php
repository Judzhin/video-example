<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Zend\Json\Decoder;
use Zend\Json\Encoder;
use Zend\Json\Json;

/**
 * Class MessageComponent
 * @package App
 */
class MessageComponent implements MessageComponentInterface
{
    /** @var CryptKey */
    protected $cryptKey;

    /** @var AccessTokenRepositoryInterface */
    protected $accessTokenRepository;

    /** @var \SplObjectStorage */
    protected $clients;

    /**
     * MessageComponent constructor.
     * @param CryptKey $cryptKey
     * @param AccessTokenRepositoryInterface $accessTokenRepository
     */
    public function __construct(CryptKey $cryptKey, AccessTokenRepositoryInterface $accessTokenRepository)
    {
        $this->cryptKey = $cryptKey;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->clients = new \SplObjectStorage;
    }

    /**
     * @inheritdoc
     *
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    /**
     * @inheritdoc
     *
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    /**
     * @inheritdoc
     *
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * @inheritdoc
     *
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        // $numRecv = count($this->clients) - 1;
        // echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
        //     , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        /** @var array $data */
        $data = Decoder::decode($msg, Json::TYPE_ARRAY);

        //if ('auth' == $data['type']) {
        //    /** @var Token $token */
        //    $token = (new Parser)->parse($data['token']);
        //
        //    if ($token->verify(new Sha256, $this->cryptKey->getKeyPath())) {
        //
        //        /** @var ValidationData $data */
        //        $data = new ValidationData;
        //        $data->setCurrentTime(time());
        //
        //        if ($token->validate($data)) {
        //            // Check if token has been revoked
        //            if (!$this->accessTokenRepository->isAccessTokenRevoked($token->getClaim('jti'))) {
        //                $from->userId = $token->getClaim('sub');
        //            }
        //        }
        //    }
        //}

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send(Encoder::encode($data));
            }
        }
    }
}
