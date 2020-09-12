<?php

namespace App\Service\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

interface ResponseMakerInterface
{
    public function makeItemResponse($item, $context = [], $httpStatus = Response::HTTP_OK): JsonResponse;
    public function makeItemsResponse($items,  $context = [], $httpStatus = Response::HTTP_OK): JsonResponse;
}