<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Handler\Author;

use App\Entity;
use App\Handler\RestHandlerTrait;
use App\Service\Video;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\StreamFactory;
use Zend\Diactoros\UploadedFile;
use Zend\Diactoros\UploadedFileFactory;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Expressive\Authentication\DefaultUser;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Filter\File\RenameUpload;
use Zend\Http\PhpEnvironment\Response;
use Zend\Stdlib\ArrayUtils;

/**
 * Class VideoHandler
 * @package App\Handler\Author
 */
class VideoHandler implements RequestHandlerInterface, ObjectManagerAwareInterface
{
    use RestHandlerTrait;
    use ProvidesObjectManager;
    use EventManagerAwareTrait;

    /** @var Entity\Author */
    private $author = null;

    /** @var Video\Preference */
    private $preference;

    /**
     * VideoHandler constructor.
     *
     * @param ObjectManager $objectManager
     * @param Video\Preference $preference
     */
    public function __construct(ObjectManager $objectManager, Video\Preference $preference)
    {
        $this->setObjectManager($objectManager);
        $this->preference = $preference;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Entity\Author
     */
    private function author(ServerRequestInterface $request): Entity\Author
    {
        if (null === $this->author) {
            /** @var DefaultUser $defaultUser */
            $defaultUser = $request->getAttribute(UserInterface::class);
            $this->author = $this
                ->getObjectManager()
                ->find(Entity\Author::class, $defaultUser->getIdentity());
        }
        return $this->author;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function get(ServerRequestInterface $request): ResponseInterface
    {
        /** @var ObjectManager $objectManager */
        $objectManager = $this->getObjectManager();

        /** @var string $id */
        if ($id = $request->getAttribute('id')) {

            /** @var Entity\Video $video */
            if (! $video = $objectManager->find(Entity\Video::class, $id)) {
                return new JsonResponse([
                    'Page Not Found'
                ], Response::STATUS_CODE_404);
            }

            /**
             * @param Entity\File $file
             * @return array
             */
            $fnSerialize = function (Entity\File $file) {
                return ArrayUtils::merge([
                    'url' => $this->preference->prependStorage($file->getPath()),
                ], $file->toArray());
            };

            return new JsonResponse([
                'id' => $video->getId(),
                'name' => $video->getName(),
                'files' => array_map($fnSerialize, $video->getFiles()),
            ], Response::STATUS_CODE_200);
        }

        /** @var ObjectRepository $repository */
        $repository = $objectManager
            ->getRepository(Entity\Video::class);

        /** @var array $criteria */
        $criteria = ['author' => $this->author($request), 'status' => Entity\Video::STATUS_ACTIVE];

        /**
         * @param Entity\Video $video
         * @return array
         */
        $fnSerialize = function (Entity\Video $video) {
            return [
                'id' => $video->getId(),
                'name' => $video->getName(),
                'thumbnail' => ArrayUtils::merge([
                    'url' => $this->preference->prependStorage($video->getThumbnail()->getPath()),
                ], $video->getThumbnail()->toArray()),
            ];
        };

        return new JsonResponse([
            'total' => $repository->count($criteria),
            'data' => array_map($fnSerialize, $repository->findBy($criteria))
        ], Response::STATUS_CODE_200);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function post(ServerRequestInterface $request): ResponseInterface
    {
        /** @var ObjectManager $objectManager */
        $objectManager = $this->getObjectManager();

        /** @var Entity\Video $video */
        $video = (new Entity\Video)
            ->setAuthor($this->author($request))
            ->setName('Some Video Name')
            ->setDate(new \DateTimeImmutable);

        /** @var RenameUpload $renameUpload */
        $renameUpload = new RenameUpload([
            'target' => $this->preference->getTarget(),
            'use_upload_name' => true,
            'use_upload_extension' => true,
            'randomize' => true,
            // @var StreamFactoryInterface $streamFactory
            'stream_factory' => new StreamFactory,
            // @var UploadedFileFactoryInterface $uploadedFileFactory
            'upload_file_factory' => new UploadedFileFactory,
        ]);

        /** @var UploadedFileInterface|UploadedFile $uploadedFile */
        foreach ($request->getUploadedFiles() as $uploadedFile) {

            /** @var UploadedFileInterface|UploadedFile $movedFile */
            $movedFile = $renameUpload->filter($uploadedFile);

            $video
                ->setOrigin(basename($movedFile->getStream()->getMetadata('uri')));

            $objectManager->persist($video);
        }

        $objectManager->flush();

        $this->getEventManager()
            ->trigger(self::class, $this, ['video' => $video]);

        return new JsonResponse([
            'id' => $video->getId()
        ], Response::STATUS_CODE_201);
    }
}
