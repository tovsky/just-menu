<?php

namespace App\Controller\API;

use App\Builder\TokenBuilder;
use App\Http\Request\AuthLoginRequest;
use App\Repository\UserRepository;
use App\Service\Http\JsonResponseMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

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

    public function __construct(
        JsonResponseMaker $jsonResponseMaker,
        UserRepository $userRepository,
        SerializerInterface  $serializer,
        UserPasswordEncoderInterface $userPasswordEncoder,
        TokenBuilder $tokenBuilder
    )
    {
        $this->jsonResponseMaker = $jsonResponseMaker;
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->tokenBuilder = $tokenBuilder;
    }

    /**
     * @Route("/login", name="api_login", methods={"POST"})
     *
     * TODO ждем Resolver от Tweeker
     */
    public function login(Request $request)
    {
        /** @var AuthLoginRequest $loginRequest */
        $loginRequest = $this->serializer->deserialize(
            $request->getContent(),
            AuthLoginRequest::class,
            JsonEncoder::FORMAT
        );

        if (null === $loginRequest->getLogin() || null === $loginRequest->getPassword()) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'login or password are empty or incorrect.');
        }

        // загружаем пользователя по логину и проверяем статус, что не заблокирован
        $user = $this->userRepository->findUserByEmail($loginRequest->getLogin());

        if (null === $user) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'login or password are empty or incorrect.');
        }

        if (false === $user->getIsActive()) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'user is blocked');
        }

        if (false === $this->userPasswordEncoder->isPasswordValid($user, $loginRequest->getPassword())) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'login or password are empty or incorrect.');
        }

        // access и refresh как строки
        $accessTokenAsString = (string)$this->tokenBuilder->buildAccessToken($user);
        $refreshTokenAsString = (string)$this->tokenBuilder->buildRefreshToken($user);


        //TODO добавить сброс имеющегося в БД Access токена (один аккаунт - одно устройство)
//        // Удаляем старый jwt если он есть
//        $oldRefreshToken = $this->refreshTokenRepository->findOneBy(['user' => $user->getId()]);
//        if (null !== $oldRefreshToken) {
//            $this->refreshTokenRepository->remove($oldRefreshToken);
//        }
//
//        // создаем новый объект refresh token и сохраняем
//        /** @var RefreshToken $refreshToken */
//        $refreshToken = RefreshToken::createNewToken($refreshTokenAsString, new \DateTime('+ 30 DAY'), $user);
//        $this->refreshTokenRepository->save($refreshToken);
        return $this->json([
            'accessToken' => $accessTokenAsString,
            'refreshToken' => $refreshTokenAsString
        ]);
    }

}
