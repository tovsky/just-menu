<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantRepository", repositoryClass=RestaurantRepository::class)
 * @ORM\Table(options={"comment":"Сведения о точке общественного питания"})
 */
class Restaurant
{
    /**
     * @ORM\Id
     * @SWG\Property(property="id", type="string")
     * @Groups({"restaurant:read"})
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", nullable=false, options={"comment":"Название ресторана"})
     * @Groups({"restaurant:read"})
     */
    private string $name;

    /**
     * @ORM\Column(type="string", nullable=false, options={"comment":"Слаг для ресторана"})
     * @Groups({"restaurant:read"})
     */
    private ?string $slug = null;

    /**
     * @ORM\Column(type="text", nullable=true, options={"comment":"Описание ресторана"})
     * @Groups({"restaurant:read"})
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", nullable=false, options={"comment":"Месторасположение ресторана"})
     * @Groups({"restaurant:read"})
     */
    private string $address;

    /**
     * @ORM\Column(type="string", nullable=true, options={"comment":"Телефон ресторана"})
     * @Groups({"restaurant:read"})
     */
    private ?string $phone = null;

    /**
     * @ORM\Column(type="string", nullable=false, options={"comment":"Почта ресторана"})
     * @Groups({"restaurant:read"})
     */
    private string $email;

    /**
     * @ORM\Column(type="string", nullable=true, options={"comment":"Сайт ресторана"})
     * @Groups({"restaurant:read"})
     * @SWG\Property(property="web_site", type="string")
     */
    private ?string $webSite = null;

    /**
     * @ORM\Column(type="string", nullable=true, options={"comment":"Наименование wi-fi"})
     * @Groups({"restaurant:read"})
     * @SWG\Property(property="wifi_name", type="string")
     */
    private ?string $wifiName = null;

    /**
     * @ORM\Column(type="string", nullable=true, options={"comment":"Пароль от wi-fi"})
     * @Groups({"restaurant:read"})
     * @SWG\Property(property="wifi_pass", type="string")
     */
    private ?string $wifiPass = null;

    /**
     * Файлы, загруженные для организации
     *
     * @ORM\ManyToMany(targetEntity=File::class, inversedBy="restaurants")
     * @SWG\Property(property="files", type="array", @SWG\Items(type="object"))
     * @Groups({"restaurant:read"})
     */
    private Collection $files;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="restaurants")
     * @SWG\Property(property="users", type="array", @SWG\Items(type="object"))
     * @Groups({"restaurant:read"})
     */
    private Collection $users;

    /**
     * @ORM\Column(type="json", nullable=false, options={"comment":"Часы работы"})
     * @SWG\Property(property="work_time", type="string")
     * @Groups({"restaurant:read"})
     */
    private string $workTime;

    /**
     * @ORM\Column(type="string", nullable=true, options={"comment":"Картинка на фон"})
     * @SWG\Property(property="background_img", type="string")
     * @Groups({"restaurant:read"})
     */
    private ?string $backgroundImg = null;

    /**
     * @ORM\Column(type="string", nullable=true, options={"comment":"Логотип"})
     * @Groups({"restaurant:read"})
     */
    private ?string $logo = null;

    /**
     * @ORM\OneToMany(targetEntity=Table::class, mappedBy="restaurant", cascade={"persist"})
     * @Groups({"restaurant:read"})
     * @SWG\Property(property="tables", type="array", @SWG\Items(type="object"))
     */
    private Collection $tables;

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
        $this->tables = new ArrayCollection();
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

    /**
     * @return Collection|Table[]
     */
    public function getTables(): Collection
    {
        return $this->tables;
    }

    public function addTable(Table $table): self
    {
        if (!$this->tables->contains($table)) {
            $this->tables[] = $table;
            $table->setRestaurant($this);
        }

        return $this;
    }

    public function removeTable(Table $table): self
    {
        if ($this->tables->contains($table)) {
            $this->tables->removeElement($table);
            // set the owning side to null (unless already changed)
            if ($table->getRestaurant() === $this) {
                $table->setRestaurant(null);
            }
        }

        return $this;
    }
}
