<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Handler;

use App\Entity\User;
use App\Handler\SignUpHandler;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Authentication\Adapter\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\PhpEnvironment\Response;
use Zend\Stdlib\ArrayUtils;

/**
 * Class SignUpHandlerTest
 * @package AppTest\Handler
 */
class SignUpHandlerTest extends TestCase
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
        $handler = new SignUpHandler($objectManager->reveal());

        /** @var ResponseInterface $response */
        $response = $handler->handle(
            $this->request([
                'email' => '',
                'password' => ''
            ])
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     *
     */
    public function testSuccess()
    {
        /** @var ObjectProphecy|ObjectManager $objectManager */
        $objectManager = $this->prophesize(EntityManager::class);

        /** @var ObjectProphecy|ObjectRepository $objectManager */
        $objectRepository = $this->prophesize(UserRepository::class);

        /** @var array $values */
        $values = ['email' => 'demo@example.com'];

        $objectRepository
            ->findOneBy($values)
            ->willReturn(null);

        $objectManager
            ->getRepository(User::class)
            ->willReturn($objectRepository->reveal());

        $objectManager
            ->persist(Argument::type(User::class))
            ->willReturn(null);

        $objectManager
            ->flush()
            ->willReturn(null);

        /** @var RequestHandlerInterface $handler */
        $handler = new SignUpHandler($objectManager->reveal());

        /** @var ResponseInterface $response */
        $response = $handler->handle(
            $this->request(ArrayUtils::merge([
                'password' => 12345678
            ], $values))
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::STATUS_CODE_201, $response->getStatusCode());
    }
}
