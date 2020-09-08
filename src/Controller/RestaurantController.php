<?php

namespace App\Controller;

use App\Builder\RestaurantBuilder;
use App\Http\Request\CreateRestaurantRequest;
use App\Service\Http\ResponseMakerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v0/restaurant")
 */
class RestaurantController
{
    private ResponseMakerInterface $responseMaker;

    public function __construct(ResponseMakerInterface $responseMaker)
    {
        $this->responseMaker = $responseMaker;
    }

    /**
     * @Route(name="create_restauranta" ,methods={"POST"})
     */
    public function create(CreateRestaurantRequest $request, RestaurantBuilder $builder, EntityManagerInterface $em): Response
    {
        $restaurant = $builder->build($request);
        $em->persist($restaurant);
        $em->flush();

        return $this->responseMaker->makeItemResponse($restaurant, [], Response::HTTP_CREATED);
    }
}