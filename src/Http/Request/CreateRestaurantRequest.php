<?php

namespace App\Http\Request;

use App\Resolver\ArgumentValueInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateRestaurantRequest implements ArgumentValueInterface
{
    /**
     * @Assert\NotBlank(groups={"App\Http\Request\CreateRestaurantRequest"})
     */
    private string $name;

    private ?string $description = null;

    /**
     * @Assert\NotBlank(groups={"App\Http\Request\CreateRestaurantRequest"})
     */
    private string $address;

    private ?string $phone = null;

    /**
     * @Assert\NotBlank(groups={"App\Http\Request\CreateRestaurantRequest"})
     * @Assert\Email(groups={"App\Http\Request\CreateRestaurantRequest"})
     */
    private string $email;

    private ?string $website = null;

    private ?string $wifiName = null;

    private ?string $wifiPass = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    public function getWifiName(): ?string
    {
        return $this->wifiName;
    }

    public function setWifiName(string $wifiName): void
    {
        $this->wifiName = $wifiName;
    }

    public function getWifiPass(): ?string
    {
        return $this->wifiPass;
    }

    public function setWifiPass(string $wifiPass): void
    {
        $this->wifiPass = $wifiPass;
    }
}