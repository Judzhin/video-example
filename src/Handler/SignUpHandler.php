<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Handler;

use App\Entity\ConfirmToken;
use App\Entity\User;
use App\Exception\ValidationException;
use App\InputFilter\SignUpInputFilter;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\Diactoros\Response\JsonResponse;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Http\PhpEnvironment\Response;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class SignUpHandler
 * @package App\Handler
 */
class SignUpHandler implements RequestHandlerInterface, ObjectManagerAwareInterface
{
    use ProvidesObjectManager;
    use EventManagerAwareTrait;

    /**
     * SignUpHandler constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->setObjectManager($objectManager);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // /** @var array $body */
        // $body = $request->getParsedBody();

        /** @var ObjectManager $objectManager */
        $objectManager = $this->getObjectManager();

        /** @var InputFilterInterface|SignUpInputFilter $inputFilter */
        $inputFilter = new SignUpInputFilter($objectManager); // Move to service declaration

        // $inputFilter->setData([
        //     'email' => $body['email'] ?? '',
        //     'password' => $body['password'] ?? ''
        // ]);

        if (! $inputFilter->setData($request->getParsedBody())->isValid()) {
            throw new ValidationException($inputFilter);

            // /** @var InputInterface $error */
            // foreach ($inputFilter->getInvalidInput() as $error) {
            //     throw new DomainException($error->getMessages());
            // }
        }

        /** @var array $values */
        $values = $inputFilter->getValues();

        /** @var User $newUser */
        $newUser = (new User)
            ->setEmail($values['email'])
            ->setPassword((new Bcrypt)->create($values['password']))
            ->setConfirmToken(ConfirmToken::create());

        $objectManager->persist($newUser);
        $objectManager->flush();

        $this->getEventManager()
            ->trigger(self::class, $this, ['entity' => $newUser]);

        return new JsonResponse([
            'email' => $newUser->getEmail(),
            'token' => $newUser->getConfirmToken()->getValue(),
        ], Response::STATUS_CODE_201);
    }
}
