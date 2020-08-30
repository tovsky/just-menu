<?php


namespace App\Service\Http;


interface ResponseMakerInterface
{
    public function makeItemResponse($item);
    public function makeItemsResponse($items);
}