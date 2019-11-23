<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Service\Video;

use App\Service\Video\Image;
use App\Service\Video\Size;
use PHPUnit\Framework\TestCase;

/**
 * Class FormatTest
 * @package AppTest\Service\Video
 */
class ImageTest extends TestCase
{
    public function testInstance()
    {
        /** @var Image $image */
        $image = new Image($path = 'path/to/image', $size = new Size(0,0));
        $this->assertEquals($path, $image->getPath());
        $this->assertSame($size, $image->getSize());
    }
}