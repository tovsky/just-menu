<?php

namespace App\Controller;

use App\Builder\RestaurantBuilder;
use App\Entity\Restaurant;
use App\Exception\ValidationException;
use App\Form\UpdateRestaurantFormType;
use App\Formatter\ConstraintViolationErrorsFormatter;
use App\Http\Request\CreateRestaurantRequest;
use App\Http\Request\UpdateRestaurantRequest;
use App\Service\Http\ResponseMakerInterface;
use App\Service\Updater\RestaurantUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v0/restaurant")
 */
class RestaurantController extends AbstractController
{
    private ResponseMakerInterface $responseMaker;

    public function __construct(ResponseMakerInterface $responseMaker)
    {
        $this->responseMaker = $responseMaker;
    }

    /**
     * @Route("/", name="create_restauranta" ,methods={"POST"})
     */
    public function create(CreateRestaurantRequest $request, RestaurantBuilder $builder, EntityManagerInterface $em): Response
    {
        $restaurant = $builder->build($request);
        $em->persist($restaurant);
        $em->flush();

        return $this->responseMaker->makeItemResponse($restaurant, [], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{slug}", name="get_restaurant", methods={"GET"})
     */
    public function getItem(Restaurant $restaurant): Response
    {
        return $this->responseMaker->makeItemResponse($restaurant, [], Response::HTTP_OK);
    }

    /**
     * @Route("/{slug}", name="update_restaurant", methods={"PUT"})
     */
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant, RestaurantUpdater $restaurantUpdater, EntityManagerInterface $em): Response
    {
        $restaurant = $restaurantUpdater->update($request, $restaurant);
        $em->persist($restaurant);
        $em->flush();

        return $this->responseMaker->makeItemResponse($restaurant, [], Response::HTTP_OK);
    }

}