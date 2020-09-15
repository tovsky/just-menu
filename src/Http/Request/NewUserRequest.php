<?php

namespace App\Http\Request;

use App\Resolver\ArgumentValueInterface;
use Symfony\Component\Validator\Constraints as Assert;

class NewUserRequest implements ArgumentValueInterface
{
    /**
     * @Assert\NotBlank(groups={"App\Http\Request\NewUserRequest"})
     */
    private string $name;

    /**
     * @Assert\NotBlank(groups={"App\Http\Request\NewUserRequest"})
     * @assert\Email(groups={"App\Http\Request\NewUserRequest"})
     */
    private string $email;

    /**
     * @Assert\NotBlank(groups={"App\Http\Request\NewUserRequest"})
     */
    private string $phone;

    /**
     * @Assert\NotBlank(groups={"App\Http\Request\NewUserRequest"})
     */
    private string $password;

    /**
     * @Assert\NotBlank(groups={"App\Http\Request\NewUserRequest"})
     */
    private string $position;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition(string $position): void
    {
        $this->position = $position;
    }
}