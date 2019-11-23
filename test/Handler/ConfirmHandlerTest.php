<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Handler;

use App\Entity\ConfirmToken;
use App\Entity\User;
use App\Handler\ConfirmHandler;
use App\Repository\UserRepository;
use AppTest\ServiceManagerAwareTrait;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\PhpEnvironment\Response;
use Zend\Stdlib\ArrayUtils;

/**
 * Class ConfirmHandlerTest
 * @package AppTest\Handler
 */
class ConfirmHandlerTest extends TestCase
{
    use ServerRequestTrait;

    /** @var ObjectProphecy|ObjectManager */
    protected $objectManager;

    /** @var ObjectProphecy|ObjectRepository */
    protected $objectRepository;

    /**
     *
     */
    protected function setUp(): void
    {
        $this->objectManager = $this->prophesize(EntityManagerInterface::class);
        $this->objectRepository = $this->prophesize(UserRepository::class);
    }

    /**
     * @expectedException \App\Exception\ValidationException
     */
    public function testValidationException()
    {
        /** @var ObjectProphecy|ObjectManager $objectManager */
        $objectManager = $this->objectManager;

        $objectManager
            ->getRepository(User::class)
            ->willReturn($this->objectRepository->reveal());

        /** @var RequestHandlerInterface $handler */
        $handler = new ConfirmHandler($objectManager->reveal());

        /** @var ResponseInterface $response */
        $handler->handle(
            $this->request([
                'email' => '',
                'token' => ''
            ])
        );
    }

    /**
     * @expectedException \MSBios\Exception\DomainException
     */
    public function testDomainException()
    {
        /** @var ObjectProphecy|ObjectManager $objectManager */
        $objectManager = $this->objectManager;

        /** @var ObjectProphecy|User $object */
        $object = $this->prophesize(User::class);
        $object
            ->isActive()
            ->willReturn(true);

        /** @var array $values */
        $values = ['email' => 'demo@example.com'];

        /** @var ObjectProphecy|ObjectRepository $objectRepository */
        $objectRepository = $this->prophesize(UserRepository::class);
        $objectRepository
            ->findOneByEmail($values['email'])
            ->willReturn($object->reveal());

        $objectRepository
            ->findOneBy($values)
            ->willReturn($object->reveal());

        $objectManager
            ->getRepository(User::class)
            ->willReturn($objectRepository->reveal());

        /** @var RequestHandlerInterface $handler */
        $handler = new ConfirmHandler($objectManager->reveal());

        /** @var ResponseInterface $response */
        $handler->handle(
            $this->request(ArrayUtils::merge([
                'token' => ConfirmToken::create()->getValue()
            ], $values))
        );
        $this->expectExceptionMessage('User is already active and ready to use.');
    }

    /**
     * @throws \Exception
     */
    public function testSuccess()
    {
        /** @var ObjectProphecy|ObjectManager $objectManager */
        $objectManager = $this->objectManager;

        /** @var ConfirmToken $confirmToken */
        $confirmToken = ConfirmToken::create();

        /** @var ObjectProphecy|User $object */
        $object = $this->prophesize(User::class);
        $object
            ->isActive()
            ->willReturn(false);

        $object
            ->getConfirmToken()
            ->willReturn($confirmToken);

        $object
            ->activate($confirmToken->getValue())
            ->willReturn($object->reveal());

        $objectManager
            ->merge($object->reveal())
            ->willReturn(null);

        $objectManager
            ->flush()
            // ->shouldHaveBeenCalled();
            ->willReturn(null);

        /** @var array $values */
        $values = ['email' => 'demo@example.com'];

        /** @var ObjectProphecy|ObjectRepository $objectRepository */
        $objectRepository = $this->prophesize(UserRepository::class);
        $objectRepository
            ->findOneByEmail($values['email'])
            ->willReturn($object->reveal());

        $objectRepository
            ->findOneBy($values)
            ->willReturn($object->reveal());

        $objectManager
            ->getRepository(User::class)
            ->willReturn($objectRepository->reveal());

        /** @var RequestHandlerInterface $handler */
        $handler = new ConfirmHandler($objectManager->reveal());

        /** @var ResponseInterface $response */
        $response = $handler->handle(
            $this->request(ArrayUtils::merge([
                'token' => $confirmToken->getValue()
            ], $values))
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::STATUS_CODE_201, $response->getStatusCode());
    }
}