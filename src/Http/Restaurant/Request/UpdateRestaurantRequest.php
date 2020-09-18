<?php

namespace App\Http\Restaurant\Request;

use App\Resolver\ArgumentValueInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateRestaurantRequest implements ArgumentValueInterface
{
    /**
     * @Assert\NotBlank(groups={"App\Http\Request\UpdateRestaurantRequest"})
     */
    private string $name;

    private ?string $description = null;

    /**
     * @Assert\NotBlank(groups={"App\Http\Request\UpdateRestaurantRequest"})
     */
    private string $address;

    private ?string $phone = null;

    /**
     * @Assert\NotBlank(groups={"App\Http\Request\UpdateRestaurantRequest"})
     * @Assert\Email(groups={"App\Http\Request\CreateRestaurantRequest"})
     */
    private string $email;

    private ?string $website = null;

    private ?string $wifiPass = null;

    private ?string $wifiName = null;

    private ?string $logo = null;

    private ?string $backgroundImg = null;

    private string $workTime;

    public function __construct()
    {
        $arrayWorkTime = [
            'Monday' => [
                'timeFrom' => null,
                'timeTo' => null
            ],
            'Tuesday' => [
                'timeFrom' => null,
                'timeTo' => null
            ],
            'Wednesday' => [
                'timeFrom' => null,
                'timeTo' => null
            ],
            'Thursday' => [
                'timeFrom' => null,
                'timeTo' => null
            ],
            'Friday' => [
                'timeFrom' => null,
                'timeTo' => null
            ],
            'Saturday' => [
                'timeFrom' => null,
                'timeTo' => null
            ],
            'Sunday' => [
                'timeFrom' => null,
                'timeTo' => null
            ],

        ];
        $this->workTime = json_encode($arrayWorkTime);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getWifiPass(): ?string
    {
        return $this->wifiPass;
    }

    public function setWifiPass(?string $wifiPass): self
    {
        $this->wifiPass = $wifiPass;

        return $this;
    }

    public function getWifiName(): ?string
    {
        return $this->wifiName;
    }

    public function setWifiName(?string $wifiName): self
    {
        $this->wifiName = $wifiName;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getBackgroundImg(): ?string
    {
        return $this->backgroundImg;
    }

    public function setBackgroundImg(?string $backgroundImg): self
    {
        $this->backgroundImg = $backgroundImg;

        return $this;
    }

    public function getWorkTime(): string
    {
        return $this->workTime;
    }

    public function setWorkTime(string $workTime): self
    {
        $this->workTime = $workTime;

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
}