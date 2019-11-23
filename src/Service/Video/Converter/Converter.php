<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video\Converter;

use App\Service\Video\Format;

use App\Service\Video\Size;
use App\Service\Video\Video;
use MSBios\Exception\RuntimeException;
use Zend\Stdlib\PriorityQueue;

/**
 * Class Converter
 * @package App\Service\Video\Converter
 */
class Converter
{
    /** @var PriorityQueue */
    protected $queue;

    /**
     * Convertor constructor.
     * @param array $resolvers
     * @codeCoverageIgnore
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
     * @return Converter
     */
    public function attach(ResolverInterface $resolver, $priority = 1): self
    {
        $this->queue->insert($resolver, $priority);
        return $this;
    }

    /**
     * @param Video $video
     * @param Format $format
     * @param Size $size
     * @return Video
     */
    public function convert(Video $video, Format $format, Size $size): Video
    {
        /** @var ResolverInterface $resolver */
        foreach ($this->queue as $resolver) {
            /** @var Video $resolve */
            if ($resolve = $resolver->resolve($video, $format, $size)) {
                return $resolve;
            }
        }

        throw new RuntimeException('Resolver for convert not found.');
    }
}
