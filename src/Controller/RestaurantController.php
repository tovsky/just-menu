<?php

namespace App\Controller;

use App\Builder\RestaurantBuilder;
use App\Entity\Restaurant;
use App\Http\Restaurant\Request\CreateRestaurantRequest;
use App\Http\Restaurant\Request\UpdateRestaurantRequest;
use App\Service\Http\ResponseMakerInterface;
use App\Service\Updater\RestaurantUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * @Route("/api/v1/restaurants")
 */
class RestaurantController extends AbstractController
{
    private ResponseMakerInterface $responseMaker;

    public function __construct(ResponseMakerInterface $responseMaker)
    {
        $this->responseMaker = $responseMaker;
    }

    //@TODO починить ответ
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
     *         description="Successful operation"
     *      )
     * )
     * @Route("/", name="api_restaurant_create" ,methods={"POST"})
     */
    public function create(
        CreateRestaurantRequest $request,
        RestaurantBuilder $builder,
        EntityManagerInterface $em
    ): Response {
        $restaurant = $builder->build($request);
        $em->persist($restaurant);
        $em->flush();
        return $this->responseMaker->makeItemResponse($restaurant, ['groups' => 'restaurant:read'], Response::HTTP_CREATED);
    }

    //@TODO починить ответ
    /**
     * @SWG\Get(
     *     summary="Get restaurant by slug",
     *     tags={"Restaurant"},
     *     description="Получение ресторана по слагу",
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     * @Route("/{slug}", name="api_restaurant_get_one", methods={"GET"})
     */
    public function getItem(Restaurant $restaurant): Response
    {
        return $this->responseMaker->makeItemResponse($restaurant, ['groups' => 'restaurant:read'], Response::HTTP_OK);
    }

    //@TODO починить ответ
    /**
     * @Route("/{slug}", name="api_restaurant_update", methods={"PUT"})
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
     *         description="Successful operation"
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

        return $this->responseMaker->makeItemResponse($restaurant, ['groups' => 'restaurant:read'], Response::HTTP_OK);
    }

}