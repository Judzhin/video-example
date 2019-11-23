<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Handler;

use App\Entity\User;
use App\Handler\ProfileHandler;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Authentication\DefaultUser;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\Json\Decoder;
use Zend\Json\Json;

/**
 * Class ProfileHandlerTest
 * @package AppTest\Handler
 */
class ProfileHandlerTest extends TestCase
{
    use ServerRequestTrait;

    /** @var ObjectProphecy|ObjectManager */
    protected $objectManager;

    /** @var ObjectProphecy|UserInterface */
    protected $defaultUser;

    /**
     *
     */
    protected function setUp(): void
    {
        $this->objectManager = $this->prophesize(EntityManagerInterface::class);
        $this->defaultUser = $this->prophesize(UserInterface::class);
        $this->defaultUser
            ->getIdentity()
            ->willReturn(getenv('TEST_IDENTIFIER'));
    }

    /**
     *
     */
    public function testPageNotFoundResponse()
    {
        /** @var ObjectProphecy|ObjectManager $objectManager */
        $objectManager = $this->objectManager;

        $objectManager
            ->find(User::class, getenv('TEST_IDENTIFIER'))
            ->willReturn(null);

        /** @var RequestHandlerInterface $handler */
        $handler = new ProfileHandler($objectManager->reveal());

        /** @var ObjectProphecy|ServerRequestInterface $request */
        $request = $this->prophesize(ServerRequestInterface::class);
        $request
            ->getAttribute(UserInterface::class)
            ->willReturn($this->defaultUser);

        /** @var ResponseInterface $response */
        $response = $handler->handle($request->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(Response::STATUS_CODE_404, $response->getStatusCode());
    }

    /**
     *
     */
    public function testSuccess()
    {
        /** @var ObjectProphecy|ObjectManager $objectManager */
        $objectManager = $this->objectManager;

        /** @var string $identity */
        $identity = getenv('TEST_IDENTIFIER');

        /** @var ObjectProphecy|User $user */
        $user = $this->prophesize(User::class);
        $user->getId()->willReturn($identity);
        $user->getEmail()->willReturn(getenv('DEMO_EMAIL'));

        $objectManager
            ->find(User::class, $identity)
            ->willReturn($user);

        /** @var RequestHandlerInterface $handler */
        $handler = new ProfileHandler($objectManager->reveal());

        /** @var ObjectProphecy|DefaultUser $defaultUser */
        $defaultUser = $this->defaultUser;
        $defaultUser
            ->getRoles()
            ->willReturn([]);
        $defaultUser
            ->getDetails()
            ->willReturn([]);

        /** @var ObjectProphecy|ServerRequestInterface $request */
        $request = $this->prophesize(ServerRequestInterface::class);
        $request
            ->getAttribute(UserInterface::class)
            ->willReturn($defaultUser);

        /** @var ResponseInterface $response */
        $response = $handler->handle($request->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertJson($content = $response->getBody()->getContents());

        /** @var array $data */
        $data = Decoder::decode($content, Json::TYPE_ARRAY);

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('default', $data);
    }
}