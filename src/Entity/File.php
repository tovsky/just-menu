<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
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
     * @ORM\Column(type="integer")
     *
     * @Groups({"file:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512, options={"comment":"Url до файла"})
     * @Groups({"file:read"})
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=512, options={"comment":"Имя файла в хранилище после загрузки"})
     * @Groups({"file:read"})
     */
    private $phisicalFileName;

    /**
     * @ORM\Column(type="string", length=512, options={"comment":"Имя загруженного файла"})
     * @Groups({"file:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", options={"comment":"Доступность для просмотра/скачки"})
     * @Groups({"file:read"})
     */
    private $isActive;

    /**
     * Дата будет проставляться автоматически
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"comment":"Дата загрузки файла"})
     * @Groups({"file:read"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="files")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Restaurant::class, mappedBy="files")
     */
    private Collection $restaurants;

    public function __construct()
    {
        $this->restaurants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getPhisicalFileName(): ?string
    {
        return $this->phisicalFileName;
    }

    public function setPhisicalFileName(string $phisicalFileName): self
    {
        $this->phisicalFileName = $phisicalFileName;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Restaurant[]
     */
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

    public function __toString()
    {
        return (string) $this->getName();
    }
}
