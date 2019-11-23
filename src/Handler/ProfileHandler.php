<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Handler;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Authentication\DefaultUser;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Http\PhpEnvironment\Response;

/**
 * Class ProfileHandler
 * @package App\Handler
 */
class ProfileHandler implements RequestHandlerInterface, ObjectManagerAwareInterface
{
    use ProvidesObjectManager;

    /**
     * ProfileHandler constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->setObjectManager($objectManager);
    }

    /**
     * @inheritdoc
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var DefaultUser $defaultUser */
        $defaultUser = $request->getAttribute(UserInterface::class);

        /** @var User $user */
        if (! $user = $this->getObjectManager()->find(User::class, $defaultUser->getIdentity())) {
            return new JsonResponse([], Response::STATUS_CODE_404);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'default' => [
                'identity' => $defaultUser->getIdentity(),
                'roles' => $defaultUser->getRoles(),
                'details' => $defaultUser->getDetails()
            ]
        ]);
    }
}
