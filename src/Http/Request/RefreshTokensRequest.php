<?php

namespace App\Http\Request;

use App\Resolver\ArgumentValueInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RefreshTokensRequest implements ArgumentValueInterface
{
    /**
     * @Assert\NotBlank(groups={"App\Http\Request\RefreshTokensRequest"})
     */
    private string $refreshToken;

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     */
    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }
}
