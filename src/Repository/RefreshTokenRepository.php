<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\RefreshTokenEntity;

/**
 * Class RefreshTokenRepository
 * @package App\Repository
 */
class RefreshTokenRepository extends EntityRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @inheritdoc
     *
     * @return RefreshTokenEntityInterface
     */
    public function getNewRefreshToken(): RefreshTokenEntityInterface
    {
        /** @var RefreshTokenEntityInterface|RefreshTokenEntity $refreshToken */
        $refreshToken = new RefreshTokenEntity;
        $refreshToken->setRevoked(false);
        return $refreshToken;
    }

    /**
     * @inheritdoc
     *
     * @param RefreshTokenEntityInterface $refreshTokenEntity
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        try {
            $this->_em->persist($refreshTokenEntity);
            $this->_em->flush();
        } catch (ORMException $exception) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
    }

    /**
     * @inheritdoc
     *
     * @param string $tokenId
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function revokeRefreshToken($tokenId): void
    {
        /** @var RefreshTokenEntityInterface|RefreshTokenEntity $refreshToken */
        $refreshToken = $this->find($tokenId);
        if ($refreshToken instanceof RefreshTokenEntityInterface) {
            $refreshToken->setRevoked(true);
            $this->_em->merge($refreshToken);
            $this->_em->flush();
        }
    }

    /**
     * @inheritdoc
     *
     * @param string $tokenId
     * @return bool
     */
    public function isRefreshTokenRevoked($tokenId): bool
    {
        /** @var RefreshTokenEntityInterface|RefreshTokenEntity $refreshToken */
        $refreshToken = $this->find($tokenId);
        if (! $refreshToken instanceof RefreshTokenEntityInterface) {
            return false;
        }

        return $refreshToken->isRevoked();
    }
}
