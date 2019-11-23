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

/**
 * Interface ResolverInterface
 * @package App\Service\Video\Thumbnailer
 */
interface ResolverInterface
{
    /**
     * @param Video $video
     * @param Size $size
     * @return Image
     */
    public function resolve(Video $video, Size $size): Image;
}
