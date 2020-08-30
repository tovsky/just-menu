<?php


namespace App\Service\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseMaker implements ResponseMakerInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function makeItemResponse($item, $context = []): JsonResponse
    {
        return new JsonResponse(
            [
                'data' => [
                    'item' => $this->serializer->normalize($item,  null, $context),
                ],
                'meta'  => [

                ]
            ]
        );
    }

    public function makeItemsResponse($items,  $context = []): JsonResponse
    {
        return new JsonResponse(
            [
                'data' => [
                    'items' => $this->serializer->normalize($items,  null, $context),
                ],
                'meta'  => [

                ]
            ]
        );
    }
}