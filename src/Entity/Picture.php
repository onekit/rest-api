<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 * @ORM\Table(name="picture")
 * @Vich\Uploadable
 */
class Picture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"picture_get", "picture_list", "Default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"picture_get", "picture_list", "Default"})
     */
    protected $title;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="App\Entity\User",
     *      inversedBy="pictures"
     * )
     * @ORM\JoinColumn(
     *      name="user_id"
     * )
     * @Groups({"picture_get", "picture_list", "Default"})
     */
    protected $user;

    /**
     * @Vich\UploadableField(mapping="picture", fileNameProperty="fileName")
     * @var File
     * @Assert\Image
     * @Assert\NotNull
     */
    protected $file;

    /**
     * @var string $fileName
     * @ORM\Column(name="filename", type="string", length=64, nullable=true)
     * @Groups({"picture_get", "picture_list", "Default"})
     */
    protected $fileName;

    /**
     * @var string $width
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Groups({"picture_get", "picture_list", "Default"})
     */
    protected $width;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Groups({"picture_get", "picture_list", "Default"})
     */
    protected $height;

    /**
     * @ORM\Column(name="file_url", type="string", length=255, nullable=true)
     * @Groups({"picture_get", "picture_list", "Default"})
     */
    protected $fileUrl;

    /**
     * @ORM\Column(name="fileSize", type="string", length=255, nullable=true)
     */
    protected $fileSize;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"picture_get", "picture_list", "Default"})
     */
    private $lat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"picture_get", "picture_list", "Default"})
     */
    private $lng;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups({"picture_get", "picture_list", "Default"})
     */
    private $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Groups({"picture_get", "picture_list", "Default"})
     */
    private $updated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(?string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @param File|null $file
     *
     * @return Picture
     */
    public function setFile(File $file = null): Picture
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    public function getFilename(): ?string
    {
        return $this->fileName;
    }

    public function setFilename(?string $filename): self
    {
        $this->fileName = $filename;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileUrl()
    {
        return $this->fileUrl;
    }

    /**
     * @param mixed $fileUrl
     */
    public function setFileUrl($fileUrl)
    {
        $this->fileUrl = $fileUrl;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getFileSize(): string
    {
        return $this->fileSize;
    }

    /**
     * @param string $fileSize
     */
    public function setFileSize(string $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return string
     */
    public function getWidth(): string
    {
        return $this->width;
    }

    /**
     * @param string $width
     */
    public function setWidth(string $width): void
    {
        $this->width = $width;
    }

    /**
     * @return string
     */
    public function getHeight(): string
    {
        return $this->height;
    }

    /**
     * @param string $height
     */
    public function setHeight(string $height): void
    {
        $this->height = $height;
    }
}
