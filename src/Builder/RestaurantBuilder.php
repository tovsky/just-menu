<?php

namespace App\Builder;

use App\Entity\Restaurant;
use App\Http\Request\CreateRestaurantRequest;
use App\Service\Generator\GeneratorQrRestaurant;
use App\Service\Generator\GeneratorSlug;

class RestaurantBuilder
{
    private GeneratorQrRestaurant $qrGenerate;

    private GeneratorSlug $generateSlug;

    public function __construct(GeneratorQrRestaurant $qrGenerate, GeneratorSlug $generateSlug)
    {
        $this->qrGenerate = $qrGenerate;
        $this->generateSlug = $generateSlug;
    }

    public function build(CreateRestaurantRequest $request): Restaurant
    {
        $restaurant = new Restaurant();

        $restaurant->setName($request->getName())
            ->setAddress($request->getAddress())
            ->setPhone($request->getPhone())
            ->setEmail($request->getEmail())
            ->setDescription($request->getDescription())
            ->setWebSite($request->getWebsite())
            ->setSlug($this->generateSlug->generate($restaurant->getName()))
            ->setWifiName($request->getWifiName())
            ->setWifiPass($request->getWifiPass());

        $this->qrGenerate->generate($restaurant->getSlug(), $restaurant->getId()->__toString());

        return $restaurant;
    }
}