<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\AccessTokenEntity;

/**
 * Class AccessTokenRepository
 * @package App\Repository
 */
class AccessTokenRepository extends EntityRepository implements AccessTokenRepositoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ClientEntityInterface $clientEntity
     * @param ScopeEntityInterface[] $scopes
     * @param mixed $userIdentifier
     *
     * @return AccessTokenEntityInterface
     */
    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        $userIdentifier = null
    ): AccessTokenEntityInterface {
        /** @var AccessTokenEntityInterface|AccessTokenEntity $accessToken */
        $accessToken = new AccessTokenEntity;
        $accessToken->setClient($clientEntity);
        $accessToken->setRevoked(false);

        /** @var ScopeEntityInterface $scope */
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        $accessToken->setUserIdentifier($userIdentifier);
        return $accessToken;
    }

    /**
     * @inheritdoc
     *
     * @param AccessTokenEntityInterface $accessTokenEntity
     * @throws UniqueTokenIdentifierConstraintViolationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        if ($this->find($accessTokenEntity->getIdentifier()) instanceof AccessTokenEntityInterface) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->_em->persist($accessTokenEntity);
        $this->_em->flush();
    }

    /**
     * @inheritdoc
     *
     * @param string $tokenId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function revokeAccessToken($tokenId): void
    {
        /** @var AccessTokenEntityInterface|AccessTokenEntity $accessToken */
        if ($accessToken = $this->find($tokenId)) {
            $accessToken->setRevoked(true);
            $this->_em->merge($accessToken);
            $this->_em->flush();
        }
    }

    /**
     * @inheritdoc
     *
     * @param string $tokenId
     * @return bool
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        /** @var AccessTokenEntityInterface|AccessTokenEntity $accessToken */
        $accessToken = $this->find($tokenId);

        if (! $accessToken instanceof AccessTokenEntityInterface) {
            return false;
        }

        return $accessToken->isRevoked();
    }
}
