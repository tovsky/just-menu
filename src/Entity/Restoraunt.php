<?php

namespace App\Entity;

use App\Repository\RestorauntRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=RestorauntRepository::class)
 * @ORM\Table(options={"comment":"Сведения о точке общественного питания"})
 */
class Restoraunt
{
    /**
     * @var UuidInterface
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512, options={"comment":"Название ресторана"})
     */
    private $name;

    /**
     *
     * @Gedmo\Slug(fields={"title", "code"})
     * @ORM\Column(type="string", length=512, options={"comment":"Слаг для ресторана"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true, options={"comment":"Описание ресторана"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=512, options={"comment":"Месторасположение ресторана"})
     */
    private $Address;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"comment":"Юридическое наименование организации"})
     */
    private $legalName;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $inn;

    /**
     * Пользователь, закрепленный к ресторану
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="restoraunts")
     */
    private $user;

    /**
     * Файлы, загруженные для организации
     *
     * @ORM\ManyToMany(targetEntity=File::class, inversedBy="restoraunts")
     */
    private $files;

    public function __construct()
    {
        $this->id = Uuid::uuid4();

        $this->files = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(?string $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getLegalName(): ?string
    {
        return $this->legalName;
    }

    public function setLegalName(?string $legalName): self
    {
        $this->legalName = $legalName;

        return $this;
    }

    public function getInn(): ?string
    {
        return $this->inn;
    }

    public function setInn(?string $inn): self
    {
        $this->inn = $inn;

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
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
        }

        return $this;
    }
}
