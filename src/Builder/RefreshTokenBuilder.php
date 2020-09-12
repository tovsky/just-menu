<?php

namespace App\Builder;

use App\Entity\RefreshToken;
use App\Entity\User;
use Ramsey\Uuid\Uuid;

class RefreshTokenBuilder
{
    public function build(string $token, \DateTime $expirationDate, User $user): RefreshToken
    {
        return (new RefreshToken())->setValue($token)
            ->setExpirationAt($expirationDate)
            ->setUser($user);
    }
}
