<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Zend\Expressive\Authentication\OAuth2\Entity\ClientEntity;

/**
 * Class ClientRepository
 * @package App\Repository
 */
class ClientRepository extends EntityRepository implements ClientRepositoryInterface
{
    /**
     * @inheritdoc
     *
     * @param string $clientIdentifier
     * @param null $grantType
     * @param null $clientSecret
     * @param bool $mustValidateSecret
     * @return ClientEntityInterface|null
     */
    public function getClientEntity(
        $clientIdentifier,
        $grantType = null,
        $clientSecret = null,
        $mustValidateSecret = true
    ): ?ClientEntityInterface {
        /** @var ClientEntity $client */
        $client = $this->find($clientIdentifier);

        /** @var ClientEntityInterface|ClientEntity $row */
        if (! $client instanceof ClientEntityInterface) {
            return null;
        }

        if (! $this->isGranted($client, $grantType)) {
            return null;
        }

        if ($mustValidateSecret && $clientSecret != $client->getSecret()) {
            return null;
        }

        return $client;
    }

    /**
     * @param ClientEntityInterface|ClientEntity $clientEntity
     * @param string $grantType
     * @return bool
     */
    private function isGranted(ClientEntityInterface $clientEntity, $grantType): bool
    {
        switch ($grantType) {
            case 'authorization_code':
                return ! ($clientEntity->hasPasswordClient() || $clientEntity->hasPersonalAccessClient());
            case 'personal_access':
                return $clientEntity->hasPersonalAccessClient();
            case 'password':
                return $clientEntity->hasPasswordClient();
            default:
                return true;
        }
    }
}
