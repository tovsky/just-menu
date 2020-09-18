<?php

namespace App\Security;

use App\Repository\UserRepository;
use App\Security\Validator\JwtTokenValidator;
use Lcobucci\JWT\Parser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var JwtTokenValidator
     */
    private JwtTokenValidator $jwtTokenValidator;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var Parser
     */
    private Parser $parser;

    public function __construct(
        JwtTokenValidator $jwtTokenValidator,
        UserRepository $userRepository,
        Parser $parser
    ) {
        $this->jwtTokenValidator = $jwtTokenValidator;
        $this->userRepository = $userRepository;
        $this->parser = $parser;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');

        return substr($authorizationHeader, 7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // проверка токена
        if (!$credentials ||
            $this->jwtTokenValidator->isTokenExpired($credentials) ||
            false === $this->jwtTokenValidator->isValidSignature($credentials)
        ) {
            throw new CustomUserMessageAuthenticationException(
                'Invalid API Token'
            );
        }

        // разбор токена
        $token = $this->parser->parse($credentials);

        $user = $this->userRepository->findActiveUserByEmail(
            json_decode($token->getClaim('user'))->email);

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // уже проверили токен в методе getUser()
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => $exception->getMessageKey(),
        ], 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
