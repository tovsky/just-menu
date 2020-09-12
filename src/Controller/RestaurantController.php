<?php

namespace App\Controller;

use App\Builder\RestaurantBuilder;
use App\Entity\Restaurant;
use App\Http\Request\CreateRestaurantRequest;
use App\Http\Request\UpdateRestaurantRequest;
use App\Service\Http\ResponseMakerInterface;
use App\Service\Updater\RestaurantUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * @Route("/api/v1/restaurant")
 */
class RestaurantController extends AbstractController
{
    private ResponseMakerInterface $responseMaker;

    public function __construct(ResponseMakerInterface $responseMaker)
    {
        $this->responseMaker = $responseMaker;
    }

    /**
     * @SWG\Post(
     *     summary="Create restaurant",
     *     tags={"Restaurant"},
     *     description="Создание ресторана",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          type="json",
     *          format="application/json",
     *          @SWG\Schema(
     *              ref=@Model(type=CreateRestaurantRequest::class)
     *          )
     *      ),
     *      @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Property(property="data", ref=@Model(type=Restaurant::class))
     *      )
     * )
     * @Route("/", name="create_restauranta" ,methods={"POST"})
     */
    public function create(
        CreateRestaurantRequest $request,
        RestaurantBuilder $builder,
        EntityManagerInterface $em
    ): Response {
        $restaurant = $builder->build($request);
        $em->persist($restaurant);
        $em->flush();

        return $this->responseMaker->makeItemResponse($restaurant, [], Response::HTTP_CREATED);
    }

    /**
     * @SWG\Get(
     *     summary="Get restaurant by slug",
     *     tags={"Restaurant"},
     *     description="Получение ресторана по слагу",
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Property(property="data", ref=@Model(type=Restaurant::class))
     *     )
     *     )
     * )
     * @Route("/{slug}", name="get_restaurant", methods={"GET"})
     */
    public function getItem(Restaurant $restaurant): Response
    {
        return $this->responseMaker->makeItemResponse($restaurant, [], Response::HTTP_OK);
    }

    /**
     * @Route("/{slug}", name="update_restaurant", methods={"PUT"})
     * @SWG\Put(
     *     summary="Update restaurant",
     *     tags={"Restaurant"},
     *     description="Обновление информации о ресторане",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          type="json",
     *          format="application/json",
     *          @SWG\Schema(
     *              ref=@Model(type=UpdateRestaurantRequest::class)
     *          )
     *      ),
     *      @SWG\Response(
     *         response=201,
     *         description="Successful operation",
     *         @SWG\Property(property="data", ref=@Model(type=Restaurant::class))
     *      )
     * )
     */
    public function update(
        UpdateRestaurantRequest $request,
        Restaurant $restaurant,
        RestaurantUpdater $restaurantUpdater,
        EntityManagerInterface $em
    ): Response
    {
        $restaurant = $restaurantUpdater->update($request, $restaurant);
        $em->persist($restaurant);
        $em->flush();

        return $this->responseMaker->makeItemResponse($restaurant, [], Response::HTTP_OK);
    }

}