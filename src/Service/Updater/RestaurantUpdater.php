<?php

namespace App\Service\Updater;

use App\Entity\Restaurant;
use App\Http\Request\UpdateRestaurantRequest;

class RestaurantUpdater
{
    public function update(UpdateRestaurantRequest $updateRestaurantRequest, Restaurant $restaurant): Restaurant
    {
        return $restaurant->setName($updateRestaurantRequest->getName())
            ->setDescription($updateRestaurantRequest->getDescription())
            ->setAddress($updateRestaurantRequest->getAddress())
            ->setPhone($updateRestaurantRequest->getPhone())
            ->setEmail($updateRestaurantRequest->getEmail())
            ->setWebSite($updateRestaurantRequest->getWebsite())
            ->setWifiPass($updateRestaurantRequest->getWifiPass())
            ->setWifiName($updateRestaurantRequest->getWifiName())
            ->setLogo($updateRestaurantRequest->getLogo())
            ->setBackgroundImg($updateRestaurantRequest->getBackgroundImg())
            ->setWorkTime($updateRestaurantRequest->getWorkTime());
    }
}