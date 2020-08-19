<?php

namespace App\DataFixtures;

use App\Entity\File;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;

class FileFixtures extends Fixture implements DependentFixtureInterface
{
    private const COUNT_FIXTURE_FILES = 5;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
        $user = $this->userRepository->findOneBy(['email' => 'admin@just-menu.ru']);
//dd($user);
        for ($i = 1; $i <= self::COUNT_FIXTURE_FILES; $i++) {
            $manager->persist($this->createFile($user, $i));
        }

        $manager->flush();
    }

    private function createFile(UserInterface $user, $iterationNumber): File
    {
        return (new File)->setUser($user)
            ->setLink($iterationNumber . '-dkfjldf-dfldkjflkd-dlfkjdlkfj-dfldjkfl')
            ->setPhisicalFileName($iterationNumber . '-phiisical-file-name')
            ->setName($iterationNumber . '-меню.pdf')
            ->setIsActive((bool) ($iterationNumber%2))
            ;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
