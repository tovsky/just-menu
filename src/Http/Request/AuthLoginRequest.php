<?php

namespace App\Http\Request;

use App\Resolver\ArgumentValueInterface;
use Symfony\Component\Validator\Constraints as Assert;

// TODO в дальнейшем здесь должно быть            implements ArgumentValueInterface
class AuthLoginRequest implements ArgumentValueInterface
{
    /**
     * @Assert\Email()
     */
    private ?string $email;

    private ?string $password;

    /**
     * @return null|string
     */
    public function getemail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setemail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
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
}
