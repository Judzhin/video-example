<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video;

/**
 * Class Image
 * @package App\Service\Video
 */
class Image
{
    /** @var string */
    protected $path;

    /** @var Size */
    protected $size;

    /**
     * Image constructor.
     * @param string $path
     * @param Size $size
     */
    public function __construct(string $path, Size $size)
    {
        $this->path = $path;
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return Size
     */
    public function getSize(): Size
    {
        return $this->size;
    }
}
