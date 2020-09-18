<?php

namespace App\Controller\API;

use App\Builder\UserBuilder;
use App\Entity\User;
use App\Http\Request\NewUserRequest;
use App\Repository\UserRepository;
use App\Service\Http\JsonResponseMaker;
use Doctrine\ORM\EntityManagerInterface;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Restaurant;

/**
 * @Route("/api/v1/users")
 */
class UserController extends AbstractController
{
    private JsonResponseMaker $jsonResponseMaker;

    private UserRepository $userRepository;

    private EntityManagerInterface $em;

    public function __construct(
        JsonResponseMaker $jsonResponseMaker,
        UserRepository $userRepository,
        EntityManagerInterface $em
    )
    {
        $this->jsonResponseMaker = $jsonResponseMaker;
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="api_user_get_all", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return $this->jsonResponseMaker->makeItemsResponse($users, ['groups' => ['user:read']]);
    }

    /**
     * @Route("/", name="api_user_save_one", methods={"POST"})
     */
    public function saveItem(NewUserRequest $newUserRequest, UserBuilder $userBuilder): JsonResponse
    {
        // TODO использовать резолвер
        $user = $userBuilder->build($newUserRequest);

        $this->em->persist($user);
        $this->em->flush();

        return $this->jsonResponseMaker->makeItemResponse($user, ['groups' => ['user:red']], Response::HTTP_CREATED);
    }

    /**
     * @Route("", name="api_user_get_one", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getItem(): JsonResponse
    {
        return $this->jsonResponseMaker->makeItemResponse($this->getUser(), ['groups' => ['user:read']]);
    }

    /**
     * @SWG\Get(
     *     summary="Get restoraunt by user",
     *     tags={"Table"},
     *     description="Получение ресторанов для пользователя",
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Property(property="data", ref=@Model(type=Restaurant::class))
     *     )
     * )
     *
     * @Route("/restaurants", name="api_user_get_all_restaurants", methods={"GET"})
     */
    public function getRestaurauntByUser(): Response
    {
        $restaurants = $this->getUser()->getRestaurants();

        return $this->jsonResponseMaker->makeItemResponse($restaurants, ['groups' => 'restaurant:read'], Response::HTTP_OK);
    }

    /**
     * @Route("/{uuid}", name="api_user_get_one_by_uuid", methods={"GET"})
     *
     * @param User $user
     * @return JsonResponse
     */
    public function getItemByUuid(User $user): JsonResponse
    {
        return $this->jsonResponseMaker->makeItemResponse($user, ['groups' => ['user:read']]);
    }
}
