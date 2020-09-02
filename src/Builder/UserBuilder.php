<?php

namespace App\Builder;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class UserBuilder
{
     private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
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

        return $user;
    }
}