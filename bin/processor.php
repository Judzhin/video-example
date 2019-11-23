<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

declare(strict_types=1);

use App\Entity;
use App\Service\Video;
use App\Service\Video\FormatDetector\FFProbeFormatDetector;
use App\Service\Video\FormatDetector\FormatDetectorInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Container\ContainerInterface;
use Zend\Json\Decoder;
use Zend\Json\Json;

(function () {

    /** @var ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';

    /** @var AMQPStreamConnection $connection */
    $connection = $container->get(AMQPStreamConnection::class);

    /** @var AMQPChannel $channel */
    $channel = $connection->channel();
    $channel->queue_declare('processing', false, false, false, true);
    $channel->exchange_declare('processing', AMQPExchangeType::FANOUT, false, false, true);
    $channel->queue_bind('processing', 'processing');

    /**
     * @var Closure
     * @param AMQPMessage $message
     */
    $onCallback = function (AMQPMessage $message) use ($container) {

        try {

            /** @var array $body */
            $body = Decoder::decode($message->body, Json::TYPE_ARRAY);

            /** @var ObjectManager $objectManager */
            $objectManager = $container->get('doctrine.entity_manager.orm_default');

            /** @var Entity\Video $video */
            $video = $objectManager->find(Entity\Video::class, $body['video_id']);

            /** @var FormatDetectorInterface $detector */
            $detector = $container->get(FFProbeFormatDetector::class);

            /** @var \App\Service\Video\Video $detected */
            $detected = $detector
                ->detect($video->getOrigin());

            /** @var Video\Thumbnailer\Thumbnailer $thumbnailer */
            $thumbnailer = $container->get(Video\Thumbnailer\Thumbnailer::class);

            /** @var Video\Preference $preference */
            $preference = $container->get(Video\Preference::class);

            /** @var Video\Image $thumb */
            $thumb = $thumbnailer->thumbnail($detected, $preference->getThumbnail());

            $video
                ->setThumbnail((new Entity\Thumbnail)
                    ->setPath($thumb->getPath())
                    ->setSize((new Entity\Size)
                        ->setWidth($thumb->getSize()->getWidth())
                        ->setHeight($thumb->getSize()->getHeight())));

            /** @var Video\Converter\Converter $converter */
            $converter = $container->get(Video\Converter\Converter::class);

            /**
             * @param Video\Video $detected
             * @param Video\Size $size
             */
            $fnAddFile = function (Video\Video $detected, Video\Size $size) use ($video, $preference, $converter) {

                /** @var Video\Format $format */
                foreach ($preference->getFormats() as $format) {
                    /** @var Video\Video $processed */
                    $processed = $converter->convert($detected, $format, $size);

                    $video->addFile((new Entity\File)
                        ->setVideo($video)
                        ->setPath($processed->getPath())
                        ->setFormat($processed->getFormat()->getName())
                        ->setSize((new Entity\Size)
                            ->setWidth($processed->getSize()->getWidth())
                            ->setHeight($processed->getSize()->getHeight())
                        )
                    );
                }
            };

            /** @var Video\Size $size */
            foreach ($preference->getVideos() as $size) {
                if ($size->lessOrEqual($detected->getSize())) {
                    $fnAddFile($detected, $size);
                    continue;
                }

                if ($detected->getSize()->lessThan($size)) {
                    $fnAddFile($detected, $size);
                }
            }

            // activate
            $video->setStatus(Entity\Video::STATUS_ACTIVE);
            $objectManager->merge($video);
            $objectManager->flush();

            $channel = $message->delivery_info['channel'];
            $channel->basic_ack($message->delivery_info['delivery_tag']);

        } catch (Throwable $throwable) {
            // Do something
        }
    };

    $channel->basic_consume(
        'processing',
        '',
        false,
        false,
        false,
        false,
        $onCallback
    );

    while (count($channel->callbacks)) {
        $channel->wait();
    }

    $channel->close();
    $connection->close();
})();