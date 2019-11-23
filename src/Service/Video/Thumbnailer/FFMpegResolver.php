<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video\Thumbnailer;

use App\Service\Video\Image;
use App\Service\Video\Size;
use App\Service\Video\Thumbnail;
use App\Service\Video\Video;
use MSBios\Exception\RuntimeException;

/**
 * Class FFMpegResolver
 * @package App\Service\Video\Thumbnailer
 */
class FFMpegResolver implements ResolverInterface
{
    /** @var string */
    private $target;

    /**
     * FFMpegResolver constructor.
     * @param $target
     */
    public function __construct($target)
    {
        $this->target = $target;
    }

    /**
     * @param Video $video
     * @param Size $size
     * @return Image
     */
    public function resolve(Video $video, Size $size): Image
    {
        /** @var string $source */
        $source = $this->target . '/' . $video->getPath();
        /** @var string $path */
        $path = $this->createFileName($video, $size);
        /** @var string $target */
        $target = $this->target . '/' . $path;
        /** @var int $width */
        $width = $size->getWidth();
        /** @var int $height */
        $height = $size->getHeight();

        exec("ffmpeg -i "
            . "{$source} -ss "
            . "00:00:01.000 -vframes "
            . "1 -s {$width}x{$height} {$target}", $output, $return);

        if (0 !== $return) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Unable to make thumbnail for ' . $video->getPath());
            // @codeCoverageIgnoreEnd
        }

        return new Image($path, $size);
    }

    /**
     * @param Video $video
     * @param Size $size
     * @return string
     */
    private function createFileName(Video $video, Size $size): string
    {
        return pathinfo($video->getPath(), PATHINFO_FILENAME)
            . '_' . $size->getWidth() . 'x' . $size->getHeight() . '.png';
    }
}
