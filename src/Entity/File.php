<?php

namespace App\Entity;

use App\Repository\FileRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FileRepository::class)
 * @ORM\Table(options={"comment":"Файлы загруженные пользователями"})
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="uuid")
     *
     * @Groups({"file:read", "user:read"})
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=512, options={"comment":"Имя файла в хранилище после загрузки"})
     * @Groups({"file:read"})
     */
    private string $physicalFileName;

    /**
     * @ORM\Column(type="string", length=512, options={"comment":"Имя загруженного файла"})
     * @Groups({"file:read"})
     */
    private string $name;

    /**
     * @ORM\Column(type="string", options={"comment":"Тип загруженного файла"})
     * @Groups({"file:read"})
     */
    private string $mimeType;

    /**
     * @ORM\Column(type="string", options={"comment":"Тип загруженного файла (меню, лого, бэкграунд)"})
     * @Groups({"file:read"})
     */
    private string $type;
    /**
     * @ORM\Column(type="integer", options={"comment":"Размер загруженного файла"})
     * @Groups({"file:read"})
     */
    private int $size;

    /**
     * Дата будет проставляться автоматически
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"comment":"Дата загрузки файла"})
     * @Groups({"file:read"})
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="files")
     */
    private UserInterface $user;

    /**
     * @ORM\ManyToMany(targetEntity=Restaurant::class, mappedBy="files")
     */
    private Collection $restaurants;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->restaurants = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPhysicalFileName(): string
    {
        return $this->physicalFileName;
    }

    public function setPhysicalFileName(string $physicalFileName): self
    {
        $this->physicalFileName = $physicalFileName;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRestaurants(): Collection
    {
        return $this->restaurants;
    }

    public function addRestaurant(Restaurant $restaurant): self
    {
        if (!$this->restaurants->contains($restaurant)) {
            $this->restaurants[] = $restaurant;
            $restaurant->addFile($this);
        }

        return $this;
    }

    public function removeRestaurant(Restaurant $restaurant): self
    {
        if ($this->restaurants->contains($restaurant)) {
            $this->restaurants->removeElement($restaurant);
            $restaurant->removeFile($this);
        }

        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getName();
    }
}
