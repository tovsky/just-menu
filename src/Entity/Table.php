<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TableRepository::class)
 * @ORM\Table(name="`table`")
 */
class Table
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @Groups({"restaurant:read","tables:read"})
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"restaurant:read", "tables:read"})
     */
    private int $number;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"restaurant:read", "tables:read"})
     */
    private string $qr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"restaurant:read", "tables:read"})
     */
    private string $status;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @Groups({"restaurant:read", "tables:read"})
     */
    private ?UserInterface $employee = null;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="tables")
     */
    private Restaurant $restaurant;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getQr(): string
    {
        return $this->qr;
    }

    public function setQr(string $qr): self
    {
        $this->qr = $qr;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(User $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }
}
