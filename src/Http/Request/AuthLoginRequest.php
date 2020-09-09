<?php

namespace App\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

// TODO в дальнейшем здесь должно быть            implements ArgumentValueInterface
class AuthLoginRequest
{
    /**
     * @Assert\Email()
     */
    private ?string $login;

    private ?string $password;

    /**
     * @return null|string
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
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
