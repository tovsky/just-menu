<?php

namespace App\Controller\API;

use App\Builder\RefreshTokenBuilder;
use App\Builder\TokenBuilder;
use App\Http\Request\AuthLoginRequest;
use App\Repository\RefreshTokenRepository;
use App\Repository\UserRepository;
use App\Service\Http\JsonResponseMaker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/auth")
 */
class AuthController extends AbstractController
{
    /**
     * @var JsonResponseMaker
     */
    private $jsonResponseMaker;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;
    /**
     * @var TokenBuilder
     */
    private TokenBuilder $tokenBuilder;
    /**
     * @var RefreshTokenRepository
     */
    private RefreshTokenRepository $refreshTokenRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var RefreshTokenBuilder
     */
    private RefreshTokenBuilder $refreshTokenBuilder;

    public function __construct(
        JsonResponseMaker $jsonResponseMaker,
        UserRepository $userRepository,
        RefreshTokenRepository $refreshTokenRepository,
        SerializerInterface  $serializer,
        UserPasswordEncoderInterface $userPasswordEncoder,
        TokenBuilder $tokenBuilder,
        RefreshTokenBuilder $refreshTokenBuilder,
        EntityManagerInterface $em
    ) {
        $this->jsonResponseMaker = $jsonResponseMaker;
        $this->userRepository = $userRepository;
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->serializer = $serializer;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->tokenBuilder = $tokenBuilder;
        $this->refreshTokenBuilder = $refreshTokenBuilder;
        $this->em = $em;
    }

    /**
     * @Route("/login", name="api_login", methods={"POST"})
     *
     * @param AuthLoginRequest $loginRequest
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function login(AuthLoginRequest $loginRequest)
    {
        // ищем активного пользователя по email
        $user = $this->userRepository->findActiveUserByEmail($loginRequest->getEmail());
        // TODO убрать if (бросать из метода резпозитория exception с  404
        if (null === $user) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'login or password are empty or incorrect.');
        }
        // TODO убрать эту логику в валидатор
        if (false === $this->userPasswordEncoder->isPasswordValid($user, $loginRequest->getPassword())) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'login or password are empty or incorrect.');
        }

        // access и refresh как строки
        $accessTokenAsString = (string)$this->tokenBuilder->buildAccessToken($user);
        $refreshTokenAsString = (string)$this->tokenBuilder->buildRefreshToken($user);

        // Удаляем старый jwt если он есть (концепция один аккаунт - одно устройство)
        $oldRefreshToken = $this->refreshTokenRepository->findOneBy(['user' => $user->getId()]);
        if (null !== $oldRefreshToken) {
            $this->em->remove($oldRefreshToken);
            $this->em->flush();
        }

        // создаем новый объект refresh token и сохраняем
        $refreshToken = $this->refreshTokenBuilder->build($refreshTokenAsString, new \DateTime('+ 30 DAY'), $user);
        $this->em->persist($refreshToken);
        $this->em->flush();

        return $this->json([
            'accessToken' => $accessTokenAsString,
            'refreshToken' => $refreshTokenAsString
        ]);
    }

}
