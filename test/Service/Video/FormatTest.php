<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Service\Video;

use App\Service\Video\Format;
use PHPUnit\Framework\TestCase;

/**
 * Class FormatTest
 * @package AppTest\Service\Video
 */
class FormatTest extends TestCase
{
    public function testInstance()
    {
        /** @var Format $format */
        $format = new Format($name = 'SomeFormatName');
        $this->assertEquals($name, $format->getName());
        $this->assertNotSame(Format::factory($name), $format);
    }
}