<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\AuthCodeEntity;

/**
 * Class AuthCodeRepository
 * @package App\Repository
 */
class AuthCodeRepository extends EntityRepository implements AuthCodeRepositoryInterface
{
    /**
     * @inheritdoc
     *
     * @return AuthCodeEntityInterface
     */
    public function getNewAuthCode(): AuthCodeEntityInterface
    {
        return new AuthCodeEntity;
    }

    /**
     * @inheritdoc
     *
     * @param AuthCodeEntityInterface $authCodeEntity
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        try {
            $this->_em->persist($authCodeEntity);
            $this->_em->flush();
        } catch (ORMException $exception) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }
    }

    /**
     * @inheritdoc
     *
     * @param string $codeId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function revokeAuthCode($codeId): void
    {
        /** @var AuthCodeEntityInterface|AuthCodeEntity $authCodeEntity */
        if ($authCodeEntity = $this->find($codeId)) {
            $authCodeEntity->setRevoked(true);
            $this->_em->merge($authCodeEntity);
            $this->_em->flush();
        }
    }

    /**
     * @inheritdoc
     *
     * @param string $codeId
     * @return bool
     */
    public function isAuthCodeRevoked($codeId): bool
    {
        /** @var AuthCodeEntityInterface|AuthCodeEntity $authCode */
        $authCode = $this->find($codeId);

        if (! $authCode instanceof AuthCodeEntityInterface) {
            return false;
        }

        return $authCode->isRevoked();
    }
}
