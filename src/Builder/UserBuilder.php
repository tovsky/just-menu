<?php

namespace App\Builder;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class UserBuilder
{
     private SerializerInterface $serializer;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        SerializerInterface $serializer,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->serializer = $serializer;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function build(Request $request): User
    {
        /** @var User $user */
        $user = $this->serializer->deserialize(
            $request->getContent(),
            User::class,
            JsonEncoder::FORMAT,
            ['groups' => 'user:create']
        );

        $user->setUuid(Uuid::v4());
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user, json_decode($request->getContent(), true)['password']
            )
        );

        return $user;
    }
}