<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Service\Video;

use App\Service\Video\Size;
use PHPUnit\Framework\TestCase;

/**
 * Class FormatTest
 * @package AppTest\Service\Video
 */
class SizeTest extends TestCase
{
    public function testInstance()
    {
        /** @var Size $size */
        $size = new Size($width = 0, $height = 0);
        $this->assertEquals($width, $size->getWidth());
        $this->assertEquals($height, $size->getHeight());

        /** @var Size $copy */
        $copy = Size::factory(['width' => 0, 'height' => 0]);
        $this->assertFalse($size->lessThan($copy));
        $this->assertTrue($size->lessOrEqual($copy));
        $this->assertIsArray($size->toArray());
    }
}