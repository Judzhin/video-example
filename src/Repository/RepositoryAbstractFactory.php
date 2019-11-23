<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Authentication\OAuth2\Entity;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class RepositoryAbstractFactory
 * @package App\Repository
 */
class RepositoryAbstractFactory implements AbstractFactoryInterface
{
    /** @var array  */
    private $mapping = [
        AccessTokenRepository::class =>
            Entity\AccessTokenEntity::class,
        AuthCodeRepository::class =>
            Entity\AuthCodeEntity::class,
        ClientRepository::class =>
            Entity\ClientEntity::class,
        RefreshTokenRepository::class =>
            Entity\RefreshTokenEntity::class,
        ScopeRepository::class =>
            Entity\ScopeEntity::class,
        UserRepository::class =>
            User::class
    ];

    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return class_exists($requestedName)
            && isset($this->mapping[$requestedName])
            && in_array(ObjectRepository::class, class_implements($requestedName), true);
    }

    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|void
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ObjectManager $objectManager */
        $objectManager = $container->get('doctrine.entity_manager.orm_default');

        return new $requestedName(
            $objectManager,
            $objectManager->getClassMetadata($this->mapping[$requestedName])
        );
    }
}
