<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video\FormatDetector;

use App\Service\Video\Format;
use App\Service\Video\Size;
use App\Service\Video\Video;
use MSBios\Exception\RuntimeException;

/**
 * Class FFProbeFormatDetector
 * @package App\Service\Video\FormatDetector
 */
class FFProbeFormatDetector implements FormatDetectorInterface
{
    /** @var string */
    private $path;

    /**
     * FFProbeFormatDetector constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @param string $path
     * @return Video
     */
    public function detect(string $path): Video
    {
        /** @var string $source */
        $source = $this->path . '/' . $path;
        return new Video(
            $path,
            $this->parseFormat($source),
            $this->parseSize($source)
        );
    }

    /**
     * @param string $source
     * @return Format
     */
    private function parseFormat(string $source): Format
    {
        return new Format(
            mb_strtolower(
                pathinfo($source, PATHINFO_EXTENSION)
            )
        );
    }

    /**
     * @param string $source
     * @return Size
     */
    private function parseSize(string $source): Size
    {
        // Console Util
        exec("ffprobe -v "
            . "error -select_streams "
            . "v:0 -show_entries "
            . "stream=width,height -of "
            . "csv=s=x:p=0 {$source}", $output, $return);

        if (0 !== $return) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Unable to get video dimensions for ' . $source);
            // @codeCoverageIgnoreEnd
        }

        if (! preg_match('#^(\d+)x(\d+)$#', $output[0], $matches)) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Incorrect dimensions for ' . $source);
            // @codeCoverageIgnoreEnd
        }

        return new Size((int)$matches[1], (int)$matches[2]);
    }
}
