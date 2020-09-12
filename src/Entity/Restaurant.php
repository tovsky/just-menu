<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantRepository", repositoryClass=RestaurantRepository::class)
 * @ORM\Table(options={"comment":"Сведения о точке общественного питания"})
 */
class Restaurant
{
    /**
     * @ORM\Id
     * @SWG\Property(property="id", type="string")
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", nullable=false, options={"comment":"Название ресторана"})
     */
    private string $name;

    /**
     * @ORM\Column(type="string", nullable=false, options={"comment":"Слаг для ресторана"})
     */
    private ?string $slug = null;

    /**
     * @ORM\Column(type="text", nullable=true, options={"comment":"Описание ресторана"})
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", nullable=false, options={"comment":"Месторасположение ресторана"})
     */
    private string $address;

    /**
     * @ORM\Column(type="string", nullable=true, options={"comment":"Телефон ресторана"})
     */
    private ?string $phone = null;

    /**
     * @ORM\Column(type="string", nullable=false, options={"comment":"Почта ресторана"})
     */
    private string $email;

    /**
     * @ORM\Column(type="string", nullable=true, options={"comment":"Сайт ресторана"})
     * @SWG\Property(property="web_site", type="string")
     */
    private ?string $webSite = null;

    /**
     * @ORM\Column(type="string", nullable=true, options={"comment":"Наименование wi-fi"})
     * @SWG\Property(property="wifi_name", type="string")
     */
    private ?string $wifiName = null;

    /**
     * @ORM\Column(type="string", nullable=true, options={"comment":"Пароль от wi-fi"})
     * @SWG\Property(property="wifi_pass", type="string")
     */
    private ?string $wifiPass = null;

    /**
     * Файлы, загруженные для организации
     *
     * @ORM\ManyToMany(targetEntity=File::class, inversedBy="restaurants")
     * @SWG\Property(property="files", type="array", @SWG\Items(type="object"))
     */
    private Collection $files;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="restaurants")
     * @SWG\Property(property="users", type="array", @SWG\Items(type="object"))
     */
    private Collection $users;

    /**
     * @ORM\Column(type="json", nullable=false, options={"comment":"Часы работы"})
     * @SWG\Property(property="work_time", type="string")
     */
    private string $workTime;

    /**
     * @ORM\Column(type="string", nullable=true, options={"comment":"Картинка на фон"})
     * @SWG\Property(property="background_img", type="string")
     */
    private ?string $backgroundImg = null;

    /**
     * @ORM\Column(type="string", nullable=true, options={"comment":"Логотип"})
     */
    private ?string $logo = null;

    public function __construct()
    {
        $this->id = Uuid::uuid4();

        $this->files = new ArrayCollection();
        $this->users = new ArrayCollection();

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


    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

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
}
