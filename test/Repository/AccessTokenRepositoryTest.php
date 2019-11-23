<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Repository;

use App\Repository\AccessTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\Expressive\Authentication\OAuth2\Entity\AccessTokenEntity;

/**
 * Class AccessTokenRepositoryTest
 * @package AppTest\Repository
 */
class AccessTokenRepositoryTest extends TestCase
{
    /** @var AccessTokenRepository */
    protected $repository;

    /** @var ObjectProphecy */
    protected $objectManager;

    /** @var ObjectProphecy */
    protected $classMetadata;

    /**
     *
     */
    public function setUp()
    {
        $this->objectManager = $this->prophesize(EntityManagerInterface::class);
        $this->classMetadata = $this->prophesize(ClassMetadata::class);
        $this->classMetadata->name = AccessTokenEntity::class;
    }

    /**
     *
     */
    public function testGetNewTokenMethod(): void
    {
        /** @var ObjectProphecy|ClientEntityInterface $clientEntity */
        $clientEntity = $this->prophesize(ClientEntityInterface::class);

        $repository = new AccessTokenRepository(
            $this->objectManager->reveal(),
            $this->classMetadata->reveal()
        );

        /** @var AccessTokenEntityInterface $accessTokenEntity */
        $accessTokenEntity = $repository->getNewToken($clientEntity->reveal(), [
            $this->prophesize(ScopeEntityInterface::class)->reveal()
        ]);

        $this->assertInstanceOf(AccessTokenEntityInterface::class, $accessTokenEntity);

    }

    /**
     * @expectedException \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     */
    public function testPersistNewAccessTokenMethodException(): void
    {
        /** @var ObjectProphecy|EntityManagerInterface $objectManager */
        $objectManager = $this->objectManager;

        /** @var ObjectProphecy|AccessTokenEntityInterface $accessTokenEntity */
        $accessTokenEntity = $this->prophesize(AccessTokenEntityInterface::class);
        $accessTokenEntity
            ->getIdentifier()
            ->willReturn('identifier');

        $objectManager
            ->find($this->classMetadata->name, 'identifier', null, null)
            ->willReturn($accessTokenEntity);

        $repository = new AccessTokenRepository(
            $objectManager->reveal(),
            $this->classMetadata->reveal()
        );

        $repository->persistNewAccessToken($accessTokenEntity->reveal());
        $this->expectExceptionMessage('Could not create unique access token identifier');
    }

    /**
     *
     */
    public function testPersistNewAccessTokenMethod(): void
    {
        /** @var ObjectProphecy|EntityManagerInterface $objectManager */
        $objectManager = $this->objectManager;

        $repository = new AccessTokenRepository(
            $objectManager->reveal(),
            $this->classMetadata->reveal()
        );

        /** @var ObjectProphecy|AccessTokenEntityInterface $accessTokenEntity */
        $accessTokenEntity = $this->prophesize(AccessTokenEntityInterface::class);
        $accessTokenEntity
            ->getIdentifier()
            ->willReturn($accessTokenEntity);

        $this->assertNull($repository->persistNewAccessToken($accessTokenEntity->reveal()));

    }
}