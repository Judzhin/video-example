<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Service\Video;

use App\Service\Video\Format;
use App\Service\Video\Preference;
use App\Service\Video\Size;
use PHPUnit\Framework\TestCase;

/**
 * Class PreferenceTest
 * @package AppTest\Service\Video
 */
class PreferenceTest extends TestCase
{
    public function testInstance()
    {
        /** @var Preference $preference */
        $preference = new Preference(
            $storage = 'path/to/storage',
            $target = 'path/to/target',
            $thumbnail = new Size(0, 0),
            $sizes = $this->videos(),
            $formats = $this->formats()
        );

        $this->assertEquals($storage . '/some/path', $preference->prependStorage('some/path'));
        $this->assertEquals($target, $preference->getTarget());
        $this->assertEquals($thumbnail, $preference->getThumbnail());
        $this->assertIsArray($preference->getVideos());
        $this->assertIsArray($preference->getFormats());
    }

    /**
     * @return \Traversable
     */
    private function videos(): \Traversable
    {
        yield new Size(0, 0);
    }

    /**
     * @return \Traversable
     */
    private function formats(): \Traversable
    {
        yield new Format('format');
    }
}