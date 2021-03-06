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
use Nelmio\ApiDocBundle\Annotation\Model;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Parser;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/auth")
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
     * @SWG\Post(
     *     summary="Login",
     *     tags={"Auth"},
     *     description="?????????????????????? ??????????????????????????",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          type="json",
     *          format="application/json",
     *          @SWG\Schema(
     *              ref=@Model(type=AuthLoginRequest::class)
     *          )
     *      ),
     *  @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                 property="accessToken",
     *                 type="string",
     *                 description="JWT access token"
     *             ),
     *             @SWG\Property(
     *                 property="refreshToken",
     *                 type="string",
     *                         description="Jwt refresh token"
     *             ),
     *             example={
     *                 "accessToken": "eyJ0eXAiOiJKV1QiLCJhbGc...",
     *                 "refreshToken": "eyJ0eXAiOiJKV1QiLCJhbGc...",
     *             }
     *         )
     *     ),
     *   @SWG\Response(response="400",description="Bad request"),
     * )
     *
     * @Route("/login", name="api_login", methods={"POST"})
     *
     * @param AuthLoginRequest $loginRequest
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function login(AuthLoginRequest $loginRequest)
    {
        // ???????? ?????????????????? ???????????????????????? ???? email
        $user = $this->userRepository->findActiveUserByEmail($loginRequest->getEmail());
        // TODO ???????????? if (?????????????? ???? ???????????? ???????????????????????? exception ??  404
        if (null === $user) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'login or password are empty or incorrect.');
        }
        // TODO ???????????? ?????? ???????????? ?? ??????????????????
        if (false === $this->userPasswordEncoder->isPasswordValid($user, $loginRequest->getPassword())) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'login or password are empty or incorrect.');
        }

        // access ?? refresh ?????? ????????????
        $accessTokenAsString = (string)$this->tokenBuilder->buildAccessToken($user);
        $refreshTokenAsString = (string)$this->tokenBuilder->buildRefreshToken($user);

        // ?????????????? ???????????? jwt ???????? ???? ???????? (?????????????????? ???????? ?????????????? - ???????? ????????????????????)
        $oldRefreshToken = $this->refreshTokenRepository->findOneBy(['user' => $user->getId()]);
        if (null !== $oldRefreshToken) {
            $this->em->remove($oldRefreshToken);
            $this->em->flush();
        }

        // ?????????????? ?????????? ???????????? refresh token ?? ??????????????????
        $refreshToken = $this->refreshTokenBuilder->build($refreshTokenAsString, new \DateTime('+ 30 DAY'), $user);
        $this->em->persist($refreshToken);
        $this->em->flush();

        return $this->json([
            'accessToken' => $accessTokenAsString,
            'refreshToken' => $refreshTokenAsString
        ]);
    }

    /**
     * @SWG\Post(
     *     summary="Logout",
     *     tags={"Auth"},
     *     description="Logout ????????????????????????",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          type="json",
     *          format="application/json",
     *          @SWG\Schema(
     *              ref=@Model(type=RefreshTokensRequest::class)
     *          )
     *      ),
     *  @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                 property="accessToken",
     *                 type="string",
     *                 description="JWT access token"
     *             ),
     *             @SWG\Property(
     *                 property="refreshToken",
     *                 type="string",
     *                         description="Jwt refresh token"
     *             ),
     *             example={}
     *         )
     *     ),
     *   @SWG\Response(response="400",description="Bad request"),
     * )
     *
     * @Route("/logout", name="api_logout", methods={"POST"})
     *
     * @param AuthLogoutRequest $logoutRequest
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function logout(AuthLogoutRequest $logoutRequest)
    {
        $user = $this->userRepository->findActiveUserByEmail($logoutRequest->getemail());

        // TODO ???????????? if (?????????????? ???? ???????????? ???????????????????????? exception ??  404
        if (null === $user) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'user not found');
        }

        $refreshTokenEntity = $this->refreshTokenRepository->findOneBy(['user' => $user->getId()]);

        // ???????? ?????? ?????????? ?????????? ???????? ???????????? ????????, ???? ??????????????
        if (null !== $refreshTokenEntity) {
            $this->em->remove($refreshTokenEntity);
            $this->em->flush();
        }

        return $this->json([]);
    }

    /**
     * @SWG\Post(
     *     summary="Refresh token",
     *     tags={"Auth"},
     *     description="???????????????????? JWT ????????????",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          type="json",
     *          format="application/json",
     *          @SWG\Schema(
     *              ref=@Model(type=AuthLogoutRequest::class)
     *          )
     *      ),
     *  @SWG\Response(
     *         response="200",
     *         description="Successful operation",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                 property="accessToken",
     *                 type="string",
     *                 description="JWT access token"
     *             ),
     *             @SWG\Property(
     *                 property="refreshToken",
     *                 type="string",
     *                         description="Jwt refresh token"
     *             ),
     *             example={
     *                 "accessToken": "eyJ0eXAiOiJKV1QiLCJhbGc...",
     *                 "refreshToken": "eyJ0eXAiOiJKV1QiLCJhbGc...",
     *              }
     *         )
     *     ),
     *  @SWG\Response(response="400",description="Bad request"),
     * )
     *
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
        // TODO ?????????????? ?? ??????????????????
        if (false === $jwtTokenValidator->isValidSignature($refreshTokensRequest->getRefreshToken()) ||
            $jwtTokenValidator->isTokenExpired($refreshTokensRequest->getRefreshToken())
        ) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'refresh token is not valid');
        }

        // ???????????? ????????????
        $token = $parser->parse($refreshTokensRequest->getRefreshToken());

        $user = $this->userRepository->findActiveUserByEmail(
            json_decode($token->getClaim('user'))->email)
        ;

        // TODO ???????????? if (?????????????? ???? ???????????? ???????????????????????? exception ??  404
        if (null === $user) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_BAD_REQUEST, 'login or password are empty or incorrect.');
        }
        /*
         * ?????? ???????????????????????? ???????????????????????? ?????????????????? ?????????????? refresh
         * ???????????????? ?????????????? ???????????????? ???? ???????? ???????????????????? ???? ????  ?? ?????????? ???????????????????? ?????????? ?? ?????????????????? ?????????????? ?? ????
         */
        $refreshTokenEntity = $this->refreshTokenRepository->findOneBy(['user' => $user->getId()]);
        if (null === $refreshTokenEntity ||
            $refreshTokenEntity->getValue() !== $refreshTokensRequest->getRefreshToken() ||
            new \DateTime() > $refreshTokenEntity->getExpirationAt()
        ) {
            return $this->jsonResponseMaker->makeItemResponse([], [], Response::HTTP_UNAUTHORIZED, 'refresh token is not valid. register required');
        }

        // access ?? refresh ?????? ????????????
        $accessTokenAsString = (string)$this->tokenBuilder->buildAccessToken($user);
        $refreshTokenAsString = (string)$this->tokenBuilder->buildRefreshToken($user);

        //???????????????? ???????????????????????? refresh ???????????????????????????? ?? ??????????????, ???????????????? ?? ?????????????? ???????????? ?????????? ????????????????????.
        $refreshTokenEntity->setValue($refreshTokenAsString);
        $this->em->persist($refreshTokenEntity);
        $this->em->flush();

        return $this->json([
            'accessToken' => $accessTokenAsString,
            'refreshToken' => $refreshTokenAsString
        ]);
    }
}
