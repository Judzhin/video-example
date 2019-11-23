<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Class Video
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="videos")
 */
class Video
{
    /**
     * @var Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue("UUID")
     */
    private $id;

    /**
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Author")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=true, name="publish_date")
     */
    private $publishDate;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $origin;

    /**
     * @var Thumbnail
     *
     * @ORM\Embedded(class="App\Entity\Thumbnail")
     */
    private $thumbnail;

    /**
     * @var ArrayCollection|File[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="video", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"size.height" = "ASC"})
     */
    private $files;

    /** @const STATUS_DRAFT */
    public const STATUS_DRAFT = 'DRAFT';

    /** @const STATUS_ACTIVE */
    public const STATUS_ACTIVE = 'ACTIVE';

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $status = self::STATUS_DRAFT;

    /**
     * Video constructor.
     */
    public function __construct()
    {
        $this->files = new ArrayCollection;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @param Uuid $id
     * @return Video
     */
    public function setId(Uuid $id): Video
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Author
     */
    public function getAuthor(): Author
    {
        return $this->author;
    }

    /**
     * @param Author $author
     * @return Video
     */
    public function setAuthor(Author $author): Video
    {
        $this->author = $author;
        return $this;
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
     * @return Video
     */
    public function setName(string $name): Video
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getPublishDate(): \DateTimeImmutable
    {
        return $this->publishDate;
    }

    /**
     * @param \DateTimeImmutable $publishDate
     * @return Video
     */
    public function setPublishDate(\DateTimeImmutable $publishDate): Video
    {
        $this->publishDate = $publishDate;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param \DateTimeImmutable $date
     * @return Video
     */
    public function setDate(\DateTimeImmutable $date): Video
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     * @return Video
     */
    public function setOrigin(string $origin): Video
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @return Thumbnail
     */
    public function getThumbnail(): Thumbnail
    {
        return $this->thumbnail;
    }

    /**
     * @param Thumbnail $thumbnail
     * @return Video
     */
    public function setThumbnail(Thumbnail $thumbnail): Video
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files->toArray();
    }

    /**
     * @param File[]|ArrayCollection $files
     * @return Video
     */
    public function setFiles($files)
    {
        /** @var File $file */
        foreach ($files as $file) {
            $this->addFile($file);
        }
        return $this;
    }

    /**
     * @param File $file
     * @return Video
     */
    public function addFile(File $file): self
    {
        $this->files->add($file);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Video
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}
