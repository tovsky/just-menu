<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512, options={"comment":"Url до файла"})
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=512, options={"comment":"Имя файла в хранилище после загрузки"})
     */
    private $phisicalFileName;

    /**
     * @ORM\Column(type="string", length=512, options={"comment":"Имя загруженного файла"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", options={"comment":"Доступность для просмотра/скачки"})
     */
    private $isActive;

    /**
     * Дата будет проставляться автоматически
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"comment":"Дата загрузки файла"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="files")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Restoraunt::class, mappedBy="files")
     */
    private $restoraunts;

    public function __construct()
    {
        $this->restoraunts = new ArrayCollection();
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
     * @return Collection|Restoraunt[]
     */
    public function getRestoraunts(): Collection
    {
        return $this->restoraunts;
    }

    public function addRestoraunt(Restoraunt $restoraunt): self
    {
        if (!$this->restoraunts->contains($restoraunt)) {
            $this->restoraunts[] = $restoraunt;
            $restoraunt->addFile($this);
        }

        return $this;
    }

    public function removeRestoraunt(Restoraunt $restoraunt): self
    {
        if ($this->restoraunts->contains($restoraunt)) {
            $this->restoraunts->removeElement($restoraunt);
            $restoraunt->removeFile($this);
        }

        return $this;
    }
}
