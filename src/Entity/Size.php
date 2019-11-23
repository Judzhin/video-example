<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\AbstractOptions;

/**
 * Class Size
 * @package App\Entity
 *
 * @ORM\Embeddable
 */
class Size extends AbstractOptions
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $width = null;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height = null;

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return Size
     */
    public function setWidth(int $width): Size
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return Size
     */
    public function setHeight(int $height): Size
    {
        $this->height = $height;
        return $this;
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
