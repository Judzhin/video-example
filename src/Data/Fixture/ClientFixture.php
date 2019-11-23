<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace Api\Data\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\Expressive\Authentication\OAuth2\Entity\ClientEntity;

/**
 * Class ClientFixture
 * @package Api\Data\Fixture
 */
class ClientFixture extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {

        /** @var ClientEntity $clientEntity */
        $clientEntity = $manager->find(ClientEntity::class, 'app') ??
            new ClientEntity('app', 'Application', '/');

        $clientEntity->setSecret('secret');
        $clientEntity->setPasswordClient(true);

        $manager->merge($clientEntity);
        $manager->flush();
    }
}
