<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Service\Video\Converter;

use App\Service\Video\Converter\Converter;
use App\Service\Video\Converter\FFMpegMp4Resolver;
use App\Service\Video\Converter\ResolverInterface;
use App\Service\Video\Format;
use App\Service\Video\Size;
use App\Service\Video\Video;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class ConverterTest
 * @package AppTest\Service\Video\Converter
 */
class ConverterTest extends TestCase
{

    public function testCallAttachMethod()
    {
        /** @var Converter $converter */
        $converter = new Converter([]);
        $this->assertInstanceOf(
            Converter::class,
            $converter->attach(new FFMpegMp4Resolver('path/to/directory'))
        );
    }

    /**
     *
     */
    public function testSuccess()
    {

        $video = $this->prophesize(Video::class);
        $format = $this->prophesize(Format::class);
        $size = $this->prophesize(Size::class);

        /** @var ObjectProphecy|ResolverInterface $resolver */
        $resolver = $this->prophesize(FFMpegMp4Resolver::class);
        $resolver
            ->resolve($video, $format, $size)
            ->willReturn($this->prophesize(Video::class));

        /** @var Converter $converter */
        $converter = new Converter;
        $converter->attach($resolver->reveal());

        /** @var Video $video */
        $video = $converter->convert($video->reveal(), $format->reveal(), $size->reveal());

        $this->assertInstanceOf(Video::class, $video);

    }

    /**
     * @expectedException \MSBios\Exception\RuntimeException
     */
    public function testException()
    {
        /** @var Converter $converter */
        $converter = new Converter;
        $converter->convert(
            $this->prophesize(Video::class)->reveal(),
            $this->prophesize(Format::class)->reveal(),
            $this->prophesize(Size::class)->reveal()
        );

        $this->expectExceptionMessage('Resolver for convert not found.');
    }



}