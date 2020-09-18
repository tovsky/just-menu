<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Generator\GeneratorSlug;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RestorauntFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var GeneratorSlug
     */
    private GeneratorSlug $generateSlug;

    public function __construct(UserRepository $userRepository, GeneratorSlug $generateSlug)
    {

        $this->userRepository = $userRepository;
        $this->generateSlug = $generateSlug;
    }

    public function load(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['uuid' => '1171a376-f6d7-4f3f-8b1c-7be79854abbd']);

        for ($i = 1; $i < 4; $i++) {
            $user->addRestaurant($this->getRestaurant($i));
        }

        $manager->persist($user);
        $manager->flush();
    }

    private function getRestaurant($iterationNumber): Restaurant
    {
        $restaurant = new Restaurant();
        $restaurant->setName('Ресторан народной китайской кухни_' . $iterationNumber)
            ->setSlug($this->generateSlug->generate($restaurant->getName()))
            ->setDescription('Вкуснейшие блюда китайских знатоков: баклажаны, курицу с арахисом в остром соусе, жареные «пельмени» с креветками. _' . $iterationNumber)
            ->setAddress('г. Москва ул. Пушкина дом 13')
            ->setPhone('84951131313')
            ->setEmail($iterationNumber . 'test@email.ru')
            ->setWebSite($iterationNumber . '_hrka.pro')
        ;

        return $restaurant;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
