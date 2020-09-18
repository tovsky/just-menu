<?php

namespace App\Http\Restaurant\Response;

use Ramsey\Uuid\UuidInterface;

class Restaurant
{
    /**
     * @SWG\Property(property="id", type="string")
     */
    private UuidInterface $id;

    private string $name;

    private ?string $slug = null;

    private ?string $description = null;

    private string $address;

    private ?string $phone = null;

    private string $email;

    /**
     * @SWG\Property(property="web_site", type="string")
     */
    private ?string $webSite = null;

    /**
     * @SWG\Property(property="wifi_name", type="string")
     */
    private ?string $wifiName = null;

    /**
     * @SWG\Property(property="wifi_pass", type="string")
     */
    private ?string $wifiPass = null;

    /**
     * @SWG\Property(property="files", type="array", @SWG\Items(type="object"))
     */
    private array $files = [];

    /**
     * @SWG\Property(property="users", type="array", @SWG\Items(type="object"))
     */
    private array $users = [];

    /**
     * @SWG\Property(property="work_time", type="string")
     */
    private string $workTime;

    /**
     * @SWG\Property(property="background_img", type="string")
     */
    private ?string $backgroundImg = null;

    private ?string $logo = null;

    private array $tables = [];

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): self
    {
        $this->id = $id;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
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

    public function getWebSite(): ?string
    {
        return $this->webSite;
    }

    public function setWebSite(?string $webSite): self
    {
        $this->webSite = $webSite;

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

    public function getWifiPass(): ?string
    {
        return $this->wifiPass;
    }

    public function setWifiPass(?string $wifiPass): self
    {
        $this->wifiPass = $wifiPass;

        return $this;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function setFiles(array $files): self
    {
        $this->files = $files;

        return $this;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function setUsers(array $users): self
    {
        $this->users = $users;

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

    public function getBackgroundImg(): ?string
    {
        return $this->backgroundImg;
    }

    public function setBackgroundImg(?string $backgroundImg): self
    {
        $this->backgroundImg = $backgroundImg;

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

    public function getTables(): ?array
    {
        return $this->tables;
    }

    public function setTables(array $tables): self
    {
        $this->tables = $tables;

        return $this;
    }
}