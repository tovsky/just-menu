<?php

namespace App\Http\Request;

use App\Resolver\ArgumentValueInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AuthLoginRequest implements ArgumentValueInterface
{
    /**
     * @Assert\Email(groups={"App\Http\Request\AuthLoginRequest"})
     * @Assert\NotBlank(groups={"App\Http\Request\AuthLoginRequest"})
     */
    private string $email;
    
    /**
     * @Assert\NotBlank(groups={"App\Http\Request\AuthLoginRequest"})
     */
    private string $password;

    public function getemail(): string
    {
        return $this->email;
    }

    public function setemail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
