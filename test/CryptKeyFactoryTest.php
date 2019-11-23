<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest;

use App\CryptKeyFactory;
use Interop\Container\ContainerInterface;
use League\OAuth2\Server\CryptKey;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class CryptKeyFactoryTest
 * @package AppTest
 */
class CryptKeyFactoryTest extends TestCase
{
    public function testInstance()
    {
        /** @var ContainerInterface|ObjectProphecy $container */
        $container = $this->prophesize(ContainerInterface::class);

        $container
            ->get('config')
            ->willReturn(['authentication' => ['public_key' => __DIR__ . '/../data/oauth/public.key']]);

        /** @var FactoryInterface $factory */
        $factory = new CryptKeyFactory;

        /** @var CryptKey $cryptKey */
        $cryptKey = $factory($container->reveal(), CryptKey::class);
        $this->assertInstanceOf(CryptKey::class, $cryptKey);
    }
}
