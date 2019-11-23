<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Service\Video\FormatDetector;

use App\Service\Video\FormatDetector\FFProbeFormatDetector;
use App\Service\Video\Size;
use App\Service\Video\Video;
use PHPUnit\Framework\TestCase;

/**
 * @param $command
 * @param array $output
 * @param $return_var
 * @return mixed
 */
function exec($command, array &$output, &$return_var)
{
    /** @var int $return_var */
    $return_var = FFProbFormatDetectorTest::$returnVar;
    return $command;
}

/**
 * Class FFProbFormatDetectorTest
 * @package AppTest\Service\Video\FormatDetector
 */
class FFProbFormatDetectorTest extends TestCase
{
    /** @var array */
    public static $output;
    /** @var int */
    public static $returnVar = 0;

    /** @var FFProbeFormatDetector */
    private $detector;

    protected function setUp()
    {
        parent::setUp();
        $this->detector = new FFProbeFormatDetector(__DIR__ . '/../../../data');
    }

    protected function tearDown()
    {
        self::$returnVar = 0;
    }

    public function testMpeg4Part14()
    {
        /** @var Video $video */
        $video = $this->detector->detect('video.mp4');
        $this->assertEquals('video.mp4', $video->getPath());
        $this->assertEquals('mp4', $video->getFormat()->getName());

        /** @var Size $size */
        $size = $video->getSize();
        $this->assertEquals(560, $size->getWidth());
        $this->assertEquals(320, $size->getHeight());
    }

    public function testThirdGenerationPartnershipProject()
    {
        /** @var Video $video */
        $video = $this->detector->detect('video.3gp');
        $this->assertEquals('video.3gp', $video->getPath());
        $this->assertEquals('3gp', $video->getFormat()->getName());

        /** @var Size $size */
        $size = $video->getSize();
        $this->assertEquals(352, $size->getWidth());
        $this->assertEquals(288, $size->getHeight());
    }

    public function testUnableToGetVideoDimensionsException()
    {
        self::$returnVar = 1;
        /** @var Video $video */
        $video = $this->detector->detect('video.mp4');
        $this->assertEquals('video.mp4', $video->getPath());
        $this->assertEquals('mp4', $video->getFormat()->getName());
    }
}