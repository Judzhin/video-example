<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Listener\Author;

use App\Entity;
use App\Service\Video;
use Kafka\Producer;
use MSBios\AMQP\AMQPStreamConnectionAwareTrait;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Zend\EventManager\EventInterface;
use Zend\Json\Encoder;

/**
 * Class VideoListener
 * @package App\Listener\Author
 */
class VideoListener
{
    // /** @var Producer */
    // protected $producer;
    use AMQPStreamConnectionAwareTrait;

    /** @var Video\Preference */
    private $preference;

    /** @var Video\FormatDetector\FFProbeFormatDetector */
    private $detector;

    /**
     * VideoListener constructor.
     * @param AMQPStreamConnection $connection
     * @param Video\Preference $preference
     * @param Video\FormatDetector\FFProbeFormatDetector $detector
     */
    // public function __construct(Producer $producer)
    public function __construct(
        AMQPStreamConnection $connection,
        Video\Preference $preference,
        Video\FormatDetector\FFProbeFormatDetector $detector
    ) {
        // $this->producer = $producer;
        $this->setAMQPStreamConnection($connection);
        $this->preference = $preference;
        $this->detector = $detector;
    }

    /**
     * @param EventInterface $event
     */
    public function __invoke(EventInterface $event): void
    {
        /** @var AMQPStreamConnection $connection */
        $connection = $this->getAMQPStreamConnection();

        /** @var AMQPChannel $channel */
        $channel = $connection->channel();

        /**
         * name: $exchange
         * type: fanout
         * passive: false // don't check is an exchange with the same name exists
         * durable: false // the exchange won't survive server restarts
         * auto_delete: true //the exchange will be deleted once the channel is closed.
         */
        $channel->exchange_declare('processing', AMQPExchangeType::FANOUT, false, false, true);

        // $channel->queue_declare('processing', false, false, false, true);

        // AMQPHelper::initProcessing($channel);
        // AMQPHelper::registerShutdown($connection, $channel);

        /** @var Entity\Video $video */
        $video = $event->getParam('video');

        /** @var \App\Service\Video\Video $detected */
        $detected = $this->detector->detect($video->getOrigin());

        /** @var AMQPMessage $message */
        $message = new AMQPMessage(Encoder::encode([
            'video_id' => $video->getId()->toString(),
            'user_id' => $video->getAuthor()->getId()->toString(),
            'detected' => $detected->toArray()
        ]), ['content_type' => 'text/plain']);

        $channel->basic_publish($message, 'processing');

        $channel->close();
        $connection->close();

        // /** @var Video\Image $thumb */
        // $thumb = $this->thumbnailer
        //     ->thumbnail($detected, $this->preference->getThumbnail());

        //$video
        //    ->setOrigin($detected->getPath())
        //    ->setThumbnail((new Entity\Thumbnail)
        //        ->setPath($thumb->getPath())
        //        ->setSize((new Entity\Size)
        //            ->setWidth($thumb->getSize()->getWidth())
        //            ->setHeight($thumb->getSize()->getHeight())));
        //

        /**
         * @param Video\Video $detected
         * @param Video\Size $size
         */
        //$fnAddFile = function (Video\Video $detected, Video\Size $size) use ($video, $channel) {
        //    /** @var Video\Format $format */
        //    foreach ($this->preference->getFormats() as $format) {
        //
        //        /** @var AMQPMessage $message */
        //        $message = new AMQPMessage(Encoder::encode([
        //            'type' => 'processing.convert',
        //            'video_id' => $video->getId(),
        //            'user_id' => $video->getAuthor()->getId(),
        //            'from' => $detected->serialize(),
        //            'to' => [
        //                'format' => $format->getName(),
        //                'size' => $size->serialize(),
        //            ],
        //
        //        ]), ['content_type' => 'text/plain']);
        //
        //        $channel->basic_publish($message, 'processing');
        //
        //        // /** @var Video\Video $processed */
        //        // $processed = $this->converter
        //        //     ->convert($detected, $format, $size);
        //        //
        //        // $video->addFile((new Entity\File)
        //        //     ->setVideo($video)
        //        //     ->setPath($processed->getPath())
        //        //     ->setFormat($processed->getFormat()->getName())
        //        //     ->setSize((new Entity\Size)
        //        //         ->setWidth($processed->getSize()->getWidth())
        //        //         ->setHeight($processed->getSize()->getHeight())
        //        //     )
        //        // );
        //    }
        //
        //    ///** @var Video\Format $format */
        //    //foreach ($this->preference->getFormats() as $format) {
        //    //    /** @var Video\Video $processed */
        //    //    $processed = $this->converter
        //    //        ->convert($detected, $format, $size);
        //    //
        //    //    $video->addFile((new Entity\File)
        //    //        ->setVideo($video)
        //    //        ->setPath($processed->getPath())
        //    //        ->setFormat($processed->getFormat()->getName())
        //    //        ->setSize((new Entity\Size)
        //    //            ->setWidth($processed->getSize()->getWidth())
        //    //            ->setHeight($processed->getSize()->getHeight())
        //    //        )
        //    //    );
        //    //}
        //};
        //
        ///** @var Video\Size $size */
        //foreach ($this->preference->getVideos() as $size) {
        //    if ($size->lessOrEqual($detected->getSize())) {
        //        $fnAddFile($detected, $size);
        //        continue;
        //    }
        //
        //    if ($detected->getSize()->lessThan($size)) {
        //        $fnAddFile($detected, $size);
        //    }
        //
        //}
        //
        // /** @var AMQPStreamConnection $connection */
        // $connection = $this->getAMQPStreamConnection();
        //
        ///** @var AMQPChannel $channel */
        //$channel = $this->getAMQPStreamConnection()->channel();
        //
        //AMQPHelper::initNotifications($channel);
        //AMQPHelper::registerShutdown($connection, $channel);
        //
        ///** @var AMQPMessage $message */
        //$message = new AMQPMessage(Encoder::encode([
        //    'type' => 'processing.thumbnail',
        //    'user_id' => $author->getId(),
        //    'message' => 'Video created'
        //]), ['content_type' => 'text/plain']);
        //
        //// $channel->basic_publish($message, AMQPHelper::EXCHANGE_NOTIFICATIONS);
        //$channel->basic_publish($message, 'processing');
    }
}
