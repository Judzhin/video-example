<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * Class ScopeRepository
 * @package App\Repository
 */
class ScopeRepository extends EntityRepository implements ScopeRepositoryInterface
{
    /**
     * @inheritdoc
     *
     * @param string $identifier
     * @return ScopeEntityInterface
     */
    public function getScopeEntityByIdentifier($identifier): ScopeEntityInterface
    {
        return $this->find($identifier);
    }

    /**
     * @inheritdoc
     *
     * @param ScopeEntityInterface[] $scopes
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @param null|string $userIdentifier
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ): array {
        return $scopes;
    }
}
