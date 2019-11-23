<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Handler;

use App\Entity\Author;
use App\Entity\User;
use App\Exception\ValidationException;
use App\InputFilter\AuthorInputFilter;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Authentication\DefaultUser;
use Zend\Expressive\Authentication\UserInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class AuthorHandler
 * @package App\Handler
 */
class AuthorHandler implements RequestHandlerInterface, ObjectManagerAwareInterface
{
    use RestHandlerTrait;
    use ProvidesObjectManager;

    /**
     * AuthorHandler constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->setObjectManager($objectManager);
    }

    /**
     * @param Author $author
     * @return ResponseInterface
     */
    private function createResponse(Author $author): ResponseInterface
    {
        return new JsonResponse([
            'id' => $author->getId(),
            'name' => $author->getName()
        ]);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function get(ServerRequestInterface $request): ResponseInterface
    {
        /** @var DefaultUser $defaultUser */
        $defaultUser = $request->getAttribute(UserInterface::class);

        /** @var Author $author */
        $author = $this->getObjectManager()
            ->find(Author::class, $defaultUser->getIdentity());

        return $this->createResponse($author);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function post(ServerRequestInterface $request): ResponseInterface
    {
        /** @var DefaultUser $defaultUser */
        $defaultUser = $request->getAttribute(UserInterface::class);

        /** @var ObjectManager $objectManager */
        $objectManager = $this->getObjectManager();

        /** @var User $user */
        $user = $objectManager->find(User::class, $defaultUser->getIdentity());

        /** @var array $data */
        $data = ArrayUtils::merge([
            'id' => $user->getId(),
        ], $request->getParsedBody());

        /** @var InputFilterInterface $inputFilter */
        $inputFilter = new AuthorInputFilter($objectManager);

        if (! $inputFilter->setData($data)->isValid()) {
            throw new ValidationException($inputFilter);
        }

        /** @var Author $author */
        $author = (new DoctrineObject($objectManager))
            ->hydrate($inputFilter->getValues(), new Author);

        $objectManager->merge($author);
        $objectManager->flush();

        return $this->createResponse($author);
    }
}
