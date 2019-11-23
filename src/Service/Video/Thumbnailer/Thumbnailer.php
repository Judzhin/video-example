<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video\Thumbnailer;

use App\Service\Video\Image;
use App\Service\Video\Size;
use App\Service\Video\Video;
use MSBios\Exception\RuntimeException;
use Zend\Stdlib\PriorityQueue;

/**
 * Class Thumbnailer
 * @package App\Service\Video\Thumbnailer
 */
class Thumbnailer
{
    /** @var PriorityQueue */
    protected $queue;

    /**
     * Thumbnailer constructor.
     * @param array $resolvers
     */
    public function __construct(array $resolvers = [])
    {
        $this->queue = new PriorityQueue;

        /** @var ResolverInterface $resolver */
        foreach ($resolvers as $resolver) {
            $this->attach($resolver);
        }
    }

    /**
     * @param ResolverInterface $resolver
     * @param int $priority
     * @return Thumbnailer
     */
    public function attach(ResolverInterface $resolver, $priority = 1): self
    {
        $this->queue->insert($resolver, $priority);
        return $this;
    }

    /**
     * @param Video $video
     * @param Size $size
     * @return Image
     */
    public function thumbnail(Video $video, Size $size): Image
    {
        /** @var ResolverInterface $resolver */
        foreach ($this->queue as $resolver) {
            /** @var Image $thumb */
            if ($thumb = $resolver->resolve($video, $size)) {
                return $thumb;
            }
        }

        new RuntimeException(sprintf(
            'Unable to find a thumbnailer from %s',
            $video->getFormat()->getName()
        ));
    }
}
