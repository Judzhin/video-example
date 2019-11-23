<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video\FormatDetector;

use App\Service\Video\Video;

/**
 * Interface FormatDetectorInterface
 * @package App\Service\Video\FormatDetector
 */
interface FormatDetectorInterface
{
    /**
     * @param string $path
     * @return Video
     */
    public function detect(string $path): Video;
}
