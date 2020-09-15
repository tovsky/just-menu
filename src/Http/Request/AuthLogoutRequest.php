<?php

namespace App\Http\Request;

use App\Resolver\ArgumentValueInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AuthLogoutRequest implements ArgumentValueInterface
{
    /**
     * @Assert\Email(groups={"App\Http\Request\AuthLogoutRequest"})
     * @Assert\NotBlank(groups={"App\Http\Request\AuthLogoutRequest"})
     */
    private string $email;

    public function getemail(): string
    {
        return $this->email;
    }

    public function setemail(string $email): void
    {
        $this->email = $email;
    }
}
