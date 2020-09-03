<?php

namespace App\Builder;

use App\Entity\Restaurant;
use App\Http\Request\CreateRestaurantRequest;

class RestaurantBuilder
{
    public function build(CreateRestaurantRequest $request): Restaurant
    {
        $restaurant = new Restaurant();

        $restaurant->setName($request->getName())
            ->setAddress($request->getAddress())
            ->setPhone($request->getPhone())
            ->setEmail($request->getEmail())
            ->setDescription($request->getDescription())
            ->setWebSite($request->getWebsite())
            ->setWifiName($request->getWifiName())
            ->setWifiPass($request->getWifiPass());

        return $restaurant;
    }
}