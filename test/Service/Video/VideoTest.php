<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Service\Video;

use App\Service\Video\Format;
use App\Service\Video\Size;
use App\Service\Video\Video;
use PHPUnit\Framework\TestCase;

/**
 * Class VideoTest
 * @package AppTest\Service\Video
 */
class VideoTest extends TestCase
{
    public function testInstance()
    {
        /** @var Video $video */
        $video = new Video(
            $path ='path/to/video',
            $format = new Format('format'),
            $size = new Size(0,0)
        );

        $this->assertEquals($path, $video->getPath());
        $this->assertSame($format, $video->getFormat());
        $this->assertSame($size, $video->getSize());
        $this->assertNotSame($size, Video::factory([
            'path' => '', 'format' => '',
            'size' => ['width' => 0, 'height' => 0],
        ]));
        $this->assertIsArray($video->toArray());
    }
}