<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Handler;

use App\Entity\User;
use App\Exception\ValidationException;
use App\InputFilter\ConfirmInputFilter;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use MSBios\Exception\DomainException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Http\PhpEnvironment\Response;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class ConfirmHandler
 * @package App\Handler
 */
class ConfirmHandler implements RequestHandlerInterface, ObjectManagerAwareInterface
{
    use ProvidesObjectManager;
    use EventManagerAwareTrait;

    /**
     * ConfirmHandler constructor.
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
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var ObjectManager $objectManager */
        $objectManager = $this->getObjectManager();

        /** @var InputFilterInterface|ConfirmInputFilter $inputFilter */
        $inputFilter = new ConfirmInputFilter($objectManager);

        if (! $inputFilter->setData($request->getParsedBody())->isValid()) {
            throw new ValidationException($inputFilter);
        }

        /** @var array $values */
        $values = $inputFilter->getValues();

        /** @var User $user */
        $user = $objectManager
            ->getRepository(User::class)
            ->findOneByEmail($values['email']);

        if ($user->isActive()) {
            throw new DomainException('User is already active and ready to use.');
        }

        $objectManager->merge($user->activate($values['token']));
        $objectManager->flush();

        $this->getEventManager()
            ->trigger(self::class, $this, ['entity' => $user]);

        return new JsonResponse([
            'success' => true,
            'messages' => [
                'activate' => 'Thanks for confirmation in system'
            ]
        ], Response::STATUS_CODE_201);
    }
}
