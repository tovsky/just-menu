<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Uid\Uuid;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createDefaultAdmin());
        $manager->persist($this->createDefaultUser());
        $manager->flush();
    }

    private function createDefaultUser(): User
    {
        $user = new User();
        $user->setEmail('user1@just-menu.ru')
            ->setUuid(Uuid::v4())
            ->setName('Юзер1 Юзерович1')
            ->setPhone('1110010203')
//            ->setRoles(['ROLE_ADMIN'])
            ->setPosition('Управляющий кафе 1')
            ->setIsActive(true)
        ;
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '1111'
        ));

        return $user;
    }

    private function createDefaultAdmin(): User
    {
        $user = new User();
        $user->setEmail('admin@just-menu.ru')
            ->setUuid(Uuid::v4())
            ->setName('admin')
            ->setPhone('9990010203')
            ->setRoles(['ROLE_ADMIN'])
            ->setPosition('admin just-menu')
            ->setIsActive(true)
        ;
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '1111'
        ));

        return $user;
    }
}
