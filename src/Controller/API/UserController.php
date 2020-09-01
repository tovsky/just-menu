<?php

namespace App\Controller\API;

use App\Builder\UserBuilder;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\Http\JsonResponseMaker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/v1/users")
 */
class UserController extends AbstractController
{
    /**
     * @var JsonResponseMaker
     */
    private $jsonResponseMaker;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

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
    public function saveItem(Request $request,UserBuilder $userBuilder): JsonResponse
    {
        $user = $userBuilder->build($request);

        $this->em->persist($user);
        $this->em->flush();

        return $this->jsonResponseMaker->makeItemResponse($user, ['groups' => ['user:read']], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{uuid}", name="api_user_get_one", methods={"GET"})
     */
    public function getItem(User $user): JsonResponse
    {
        return $this->jsonResponseMaker->makeItemResponse($user, ['groups' => ['user:read']]);
    }


//    /**
//     * @Route("/{uuid}}", name="api_user_update", methods={"PUT"})
//     */
//    public function updateItem(User $user): JsonResponse
//    {
//
//    }
//
//    /**
//     * @Route("/{uuid}", name="api_user_delete", methods={"DELETE"})
//     */
//    public function deleteItem(User $user): JsonResponse
//    {
//
//    }
}
