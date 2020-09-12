<?php


namespace App\Service\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

    public function makeItemResponse($item, $context = [], $httpStatus = Response::HTTP_OK, string $comment = ''): JsonResponse
    {
        return new JsonResponse(
            [
                'data' => [
                    $this->serializer->normalize($item,  null, $context),
                ],
                'meta'  => [
                    'comment' => $comment,
                ]
            ],
            $httpStatus
        );
    }

    public function makeItemsResponse($items,  $context = [], $httpStatus = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse(
            [
                'data' => [
                    'items' => $this->serializer->normalize($items,  null, $context),
                ],
                'meta'  => [

                ],
            ],
            $httpStatus
        );
    }
}