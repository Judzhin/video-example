<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video;

/**
 * Class Preference
 * @package App\Service\Video
 */
class Preference
{
    /** @var string */
    protected $storage;

    /** @var string */
    protected $target;

    /** @var Size */
    protected $thumbnail;

    /** @var Size[] */
    protected $videos = [];

    /** @var Format[] */
    protected $formats = [];

    /**
     * Preference constructor.
     * @param string $storage
     * @param string $target
     * @param Size $thumbnail
     * @param \Traversable $videos
     * @param \Traversable $formats
     */
    public function __construct(
        string $storage,
        string $target,
        Size $thumbnail,
        \Traversable $videos,
        \Traversable $formats
    ) {
        $this->storage = $storage;
        $this->target = $target;

        $this->setThumbnail($thumbnail);

        /** @var Size $size */
        foreach ($videos as $size) {
            $this->addVideo($size);
        }

        /** @var Format $format */
        foreach ($formats as $format) {
            $this->addFormat($format);
        }
    }

    /**
     * @param $path
     * @return string
     */
    public function prependStorage($path)
    {
        return $this->storage . '/' . $path;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @return Size
     */
    public function getThumbnail(): Size
    {
        return $this->thumbnail;
    }

    /**
     * @param Size $thumbnail
     * @return Preference
     */
    public function setThumbnail(Size $thumbnail): Preference
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    /**
     * @return Size[]
     */
    public function getVideos(): array
    {
        return $this->videos;
    }

    /**
     * @param Size $size
     * @return Preference
     */
    public function addVideo(Size $size): self
    {
        array_push($this->videos, $size);
        return $this;
    }

    /**
     * @return Format[]
     */
    public function getFormats(): array
    {
        return $this->formats;
    }

    /**
     * @param Format $format
     * @return Preference
     */
    public function addFormat(Format $format): self
    {
        array_push($this->formats, $format);
        return $this;
    }
}
