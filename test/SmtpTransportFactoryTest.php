<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest;

use App\MessageComponent;
use App\SmtpTransportFactory;
use Interop\Container\ContainerInterface;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Ratchet\ConnectionInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Json\Encoder;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\TransportInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class SmtpTransportFactoryTest
 * @package AppTest
 */
class SmtpTransportFactoryTest extends TestCase
{
    /**
     *
     */
    public function testInstance()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'smtp_options' => []
        ]);
        /** @var FactoryInterface $factory */
        $factory = new SmtpTransportFactory;

        /** @var TransportInterface $transport */
        $transport = $factory($container->reveal(), Smtp::class);

        $this->assertInstanceOf(TransportInterface::class, $transport);
        $this->assertInstanceOf(Smtp::class, $transport);
    }
}
