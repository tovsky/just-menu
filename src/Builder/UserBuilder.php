<?php

namespace App\Builder;

use App\Entity\User;
use App\Http\Request\NewUserRequest;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Uid\Uuid;

class UserBuilder
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function build(NewUserRequest $newUserRequest): User
    {
        $user = new User();
        $user->setName($newUserRequest->getName())
            ->setEmail($newUserRequest->getEmail())
            ->setPhone($newUserRequest->getPhone())
            ->setPosition($newUserRequest->getPosition());

        $user->setUuid(Uuid::v4());
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user, $newUserRequest->getPassword()
            )
        );

        return $user;
    }
}