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

/**
 * Interface ResolverInterface
 * @package App\Service\Video\Converter
 */
interface ResolverInterface
{
    /**
     * @param Video $video
     * @param Format $format
     * @param Size $size
     * @return Video|null
     */
    public function resolve(Video $video, Format $format, Size $size): ?Video;
}
