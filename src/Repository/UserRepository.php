<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\Expressive\Authentication\OAuth2\Entity\UserEntity;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * @param string $token
     * @return User|null
     */
    public function findOneByConfirmToken(string $token): ?User
    {
        return $this->findOneBy(['confirmToken.value' => $token]);
    }

    /**
     * Hold form unittest
     *
     * @param $email
     * @return null|object
     */
    public function findOneByEmail($email)
    {
        return parent::findOneBy(['email' => $email]);
    }

    /**
     * @inheritdoc
     *
     * @param string $username
     * @param string $password
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @return UserEntityInterface|null
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): ?UserEntityInterface {
        /** @var User $user */
        if (! $user = $this->findOneByEmail($username)) {
            return null;
        }

        if ((new Bcrypt)->verify($password, $user->getPassword())) {
            return new UserEntity($user->getId());
        }

        return null;
    }
}
