<?php

namespace App\Http\Restaurant\Response;

use App\Entity\Restaurant;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Table
{
    private UuidInterface $id;

    private int $number;

    private string $qr;

    private string $status;

    private ?UserInterface $employee = null;

    private Restaurant $restaurant;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     */
    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getQr(): string
    {
        return $this->qr;
    }

    /**
     * @param string $qr
     */
    public function setQr(string $qr): void
    {
        $this->qr = $qr;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return UserInterface|null
     */
    public function getEmployee(): ?UserInterface
    {
        return $this->employee;
    }

    /**
     * @param UserInterface|null $employee
     */
    public function setEmployee(?UserInterface $employee): void
    {
        $this->employee = $employee;
    }

    /**
     * @return Restaurant
     */
    public function getRestaurant(): Restaurant
    {
        return $this->restaurant;
    }

    /**
     * @param Restaurant $restaurant
     */
    public function setRestaurant(Restaurant $restaurant): void
    {
        $this->restaurant = $restaurant;
    }
}