<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video;

/**
 * Class Format
 * @package App\Service\Video
 */
class Format
{
    /** @var string */
    protected $name;

    /**
     * Format constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Format
     */
    public static function factory(string $name): self
    {
        return new self($name);
    }
}
