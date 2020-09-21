<?php

namespace App\Http\Request\File;

use App\Entity\Restaurant;
use App\Entity\User;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UploadFileRequest
{
    /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"application/pdf", "application/x-pdf", "image/jpeg", "image/png"},
     *     mimeTypesMessage = "Please upload a valid PDF"
     * )
     * @Assert\NotBlank()
     * @SWG\Property(property="file", type="array", @SWG\Items(type="object"))
     */
    private ?UploadedFile $file = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Choice({"background", "logo", "menu"})
     */
    private string $type;

    /**
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @SWG\Property(property="restaurant", type="array", @SWG\Items(type="object"))
     */
    private Restaurant $restaurant;

    /**
     * @Assert\NotBlank()
     * @SWG\Property(property="user", type="array", @SWG\Items(type="object"))
     */
    private UserInterface $user;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRestaurant(): Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(Restaurant $restaurant): void
    {
        $this->restaurant = $restaurant;
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): void
    {
        $this->file = $file;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }
}