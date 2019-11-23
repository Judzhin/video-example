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

/**
 * Class FFMpegWebmResolver
 * @package App\Service\Video\Converter
 */
class FFMpegWebmResolver implements ResolverInterface
{
    /** @var string */
    protected $path;

    /** @const FORMAT_RESOLVE */
    private const FORMAT_RESOLVE = 'webm';

    /**
     * FFMpegMp4Resolver constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @param Video $video
     * @param Format $format
     * @param Size $size
     * @return Video|null
     */
    public function resolve(Video $video, Format $format, Size $size): ?Video
    {
        // Must resolve to mp4 format
        if (self::FORMAT_RESOLVE !== $format->getName()) {
            return null;
        }

        /** @var string $source */
        $source = $this->path . '/' . $video->getPath();
        /** @var string $path */
        $path = $this->createFileName($video, $size);
        /** @var string $target */
        $target = $this->path . '/' . $path;

        /** @var int $width */
        $width = $size->getWidth();

        /** @var int $height */
        $height = $size->getHeight();

        exec("ffmpeg -i "
            . "{$source} -f "
            . "webm -vcodec "
            . "libvpx -acodec "
            . "libvorbis -crf "
            . "22 -vf "
            . "'scale={$width}:{$height}:force_original_aspect_ratio=decrease,"
            . "pad={$width}:{$height}:x=({$width}-iw)/2:y=({$height}-ih)/2:color=black' {$target}", $output, $return);

        if (0 !== $return) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Unable to convert ' . $video->getPath() . ' to ' . $format->getName());
            // @codeCoverageIgnoreEnd
        }

        return new Video($path, $format, $size);
    }

    /**
     * @param Video $video
     * @param Size $size
     * @return string
     */
    private function createFileName(Video $video, Size $size): string
    {
        return pathinfo($video->getPath(), PATHINFO_FILENAME)
            . '_' . $size->getWidth() . 'x' . $size->getHeight() . '.' . self::FORMAT_RESOLVE;
    }
}
