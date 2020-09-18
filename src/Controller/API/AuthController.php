<?php

namespace App\Controller\API;

use App\Builder\RefreshTokenBuilder;
use App\Builder\TokenBuilder;
use App\Http\Request\AuthLoginRequest;
use App\Http\Request\AuthLogoutRequest;
use App\Http\Request\RefreshTokensRequest;
use App\Repository\RefreshTokenRepository;
use App\Repository\UserRepository;
use App\Service\Http\JsonResponseMaker;
use App\Security\Validator\JwtTokenValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/auth")
 */
class AuthController extends AbstractController
{
    private JsonResponseMaker $jsonResponseMaker;

    private UserRepository $userRepository;

    private SerializerInterface $serializer;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    private TokenBuilder $tokenBuilder;

    private RefreshTokenRepository $refreshTokenRepository;

    private EntityManagerInterface $em;

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
     * @todo поправить ошибку когда 2 раза подряд логинится пользователь (не делая logout)
     *
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

    /**
     * @Route("/logout", name="api_logout", methods={"POST"})
     *
     * @param AuthLogoutRequest $logoutRequest
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function logout(AuthLogoutRequest $logoutRequest)
    {
        $user = $this->userRepository->findActiveUserByEmail($logoutRequest->getemail());

        // TODO убрать if (бросать из метода резпозитория exception с  404
        if (null === $user) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'user not found');
        }

        $refreshTokenEntity = $this->refreshTokenRepository->findOneBy(['user' => $user->getId()]);

        // если для этого юзера есть рефреш токе, то удаляем
        if (null !== $refreshTokenEntity) {
            $this->em->remove($refreshTokenEntity);
            $this->em->flush();
        }

        return $this->json([]);
    }

    /**
     * @Route("/refresh-tokens", name="api_refresh_tokens", methods={"POST"})
     *
     * @param RefreshTokensRequest $refreshTokensRequest
     * @param JwtTokenValidatorInterface $jwtTokenValidator
     * @param Parser $parser
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function refreshTokens(RefreshTokensRequest $refreshTokensRequest, JwtTokenValidatorInterface $jwtTokenValidator, Parser $parser): JsonResponse
    {
        // TODO вынести в валидатор
        if (false === $jwtTokenValidator->isValidSignature($refreshTokensRequest->getRefreshToken()) ||
            $jwtTokenValidator->isTokenExpired($refreshTokensRequest->getRefreshToken())
        ) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'refresh token is not valid');
        }

        // разбор токена
        $token = $parser->parse($refreshTokensRequest->getRefreshToken());

        $user = $this->userRepository->findActiveUserByEmail(
            json_decode($token->getClaim('user'))->email)
        ;

        // TODO убрать if (бросать из метода резпозитория exception с  404
        if (null === $user) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'login or password are empty or incorrect.');
        }
        /*
         * для загруженного пользователя проверяем наличие refresh
         * проверка времени действия по дате эксприации из БД  и также сравниваем токен с имеющимся токеном в БД
         */
        $refreshTokenEntity = $this->refreshTokenRepository->findOneBy(['user' => $user->getId()]);
        if (null === $refreshTokenEntity ||
            $refreshTokenEntity->getValue() !== $refreshTokensRequest->getRefreshToken() ||
            new \DateTime() > $refreshTokenEntity->getExpirationAt()
        ) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_UNAUTHORIZED, 'refresh token is not valid. register required');
        }

        // access и refresh как строки
        $accessTokenAsString = (string)$this->tokenBuilder->buildAccessToken($user);
        $refreshTokenAsString = (string)$this->tokenBuilder->buildRefreshToken($user);

        //значение обновленного refresh перезаписываем в таблицу, сохранив в таблице старое время экспирации.
        $refreshTokenEntity->setValue($refreshTokenAsString);
        $this->em->persist($refreshTokenEntity);
        $this->em->flush();

        return $this->json([
            'accessToken' => $accessTokenAsString,
            'refreshToken' => $refreshTokenAsString
        ]);
    }
}
