<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Service\Video\Converter;

use App\Service\Video\Converter\FFMpegMp4Resolver;
use App\Service\Video\Converter\ResolverInterface;
use App\Service\Video\Format;
use App\Service\Video\Size;
use App\Service\Video\Video;
use PHPUnit\Framework\TestCase;

/**
 * Class FFMpegMp4ResolverTest
 * @package AppTest\Service\Video\Converter
 */
class FFMpegMp4ResolverTest extends TestCase
{
    /** @var string */
    private $path;

    /** @var ResolverInterface */
    private $resolver;

    protected function setUp()
    {
        parent::setUp();
        $this->path = __DIR__ . '/../../../data';
        $this->resolver = new FFMpegMp4Resolver($this->path);
    }

    public function testFailureFormat()
    {
        $this->assertNull(
            $this->resolver->resolve(
                new Video('video.mp4', new Format('mp4'), new Size(560, 320)),
                new Format('webm'),
                new Size(560, 320)
            )
        );
    }

    public function testSuccess()
    {
        if (file_exists($filename = $this->path . '/video_320x240.mp4')) {
            unlink($filename);
        }

        /** @var Video $thumb */
        $thumb = $this->resolver->resolve(
            new Video('video.3gp', new Format('3gp'), new Size(352, 288)),
            new Format('mp4'),
            new Size(320, 240)
        );

        $this->assertEquals('video_320x240.mp4', $thumb->getPath());

        /** @var Size $size */
        $size = $thumb->getSize();
        $this->assertEquals(320, $size->getWidth());
        $this->assertEquals(240, $size->getHeight());
        $this->assertFileExists($filename);
    }


}