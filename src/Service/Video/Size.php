<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video;

/**
 * Class Size
 * @package App\Service\Video
 */
class Size
{
    /** @var int */
    protected $width;

    /** @var int */
    protected $height;

    /**
     * Size constructor.
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param self $size
     * @return bool
     */
    public function lessThan(self $size): bool
    {
        return $this->getHeight() < $size->getHeight();
    }

    /**
     * @param self $size
     * @return bool
     */
    public function lessOrEqual(self $size): bool
    {
        return $this->getHeight() <= $size->getHeight();
    }

    /**
     * @param array $options
     * @return Size
     */
    public static function factory(array $options): self
    {
        return new self($options['width'], $options['height']);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
        ];
    }
}
