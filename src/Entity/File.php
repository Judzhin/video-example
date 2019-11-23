<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class File
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="files")
 */
class File
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue("UUID")
     */
    private $id;
    /**
     * @var Video
     * @ORM\ManyToOne(targetEntity="Video")
     * @ORM\JoinColumn(name="video_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $video;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $path;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $format;
    /**
     * @var Size
     * @ORM\Embedded(class="Size")
     */
    private $size;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return File
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Video
     */
    public function getVideo(): Video
    {
        return $this->video;
    }

    /**
     * @param Video $video
     * @return File
     */
    public function setVideo(Video $video): File
    {
        $this->video = $video;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return File
     */
    public function setPath(string $path): File
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return File
     */
    public function setFormat(string $format): File
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return Size
     */
    public function getSize(): Size
    {
        return $this->size;
    }

    /**
     * @param Size $size
     * @return File
     */
    public function setSize(Size $size): File
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'path' => $this->getPath(),
            'format' => $this->getFormat(),
            'size' => $this->getSize()->toArray()
        ];
    }
}
