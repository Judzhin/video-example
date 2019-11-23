<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video;

/**
 * Class Video
 * @package App\Service\Video
 */
class Video
{
    /** @var string */
    protected $path;

    /** @var Format */
    protected $format;

    /** @var Size */
    protected $size;

    /**
     * Video constructor.
     * @param string $path
     * @param Format $format
     * @param Size $size
     */
    public function __construct(string $path, Format $format, Size $size)
    {
        $this->path = $path;
        $this->format = $format;
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
     * @return Format
     */
    public function getFormat(): Format
    {
        return $this->format;
    }

    /**
     * @return Size
     */
    public function getSize(): Size
    {
        return $this->size;
    }

    /**
     * @param $options
     * @return Video
     */
    public static function factory($options): self
    {
        return new self(
            $options['path'],
            Format::factory($options['format']),
            Size::factory($options['size'])
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'path' => $this->getPath(),
            'format' => $this->getFormat()->getName(),
            'size' => $this->getSize()->toArray(),
        ];
    }
}
