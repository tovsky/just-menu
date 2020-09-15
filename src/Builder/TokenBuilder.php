<?php


namespace App\Builder;


use App\Entity\User;
use App\Security\Key\KeyProviderInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Token;
use Symfony\Component\Serializer\SerializerInterface;

class TokenBuilder
{
    private KeyProviderInterface $keyProvider;

    private SerializerInterface $serializer;

    private Rsa $singer;

    private int $expirationTimeAccess;

    private int $expirationTimeRefresh;

    public function __construct(
        KeyProviderInterface $keyProvider,
        Rsa $singer,
        SerializerInterface $serializer,
        int $expirationTimeAccess = 3600, // 1 час
        int $expirationTimeRefresh = 2592000 // 30 дней
    )
    {
        $this->keyProvider = $keyProvider;
        $this->serializer = $serializer;
        $this->expirationTimeAccess = $expirationTimeAccess;
        $this->singer = $singer;
        $this->expirationTimeRefresh = $expirationTimeRefresh;
    }

    /**
     * @param User $user
     * @return \Lcobucci\JWT\Token
     */
    public function buildAccessToken(User $user): Token
    {
        $time = time() + $this->expirationTimeAccess;
        $accessToken = (new Builder())->expiresAt($time)
            ->withClaim('user', $this->serializer->serialize($user, 'json', ['groups' => 'jwt:access']))
            ->getToken($this->singer, $this->keyProvider->getPrivateKey());

        return $accessToken;
    }

    /**
     * @param User $user
     * @return \Lcobucci\JWT\Token
     */
    public function buildRefreshToken(User $user): Token
    {
        $time = time() + $this->expirationTimeRefresh;
        $refreshToken = (new Builder())->expiresAt($time)
            ->withClaim('user', $this->serializer->serialize($user, 'json', ['groups' => 'jwt:refresh']))
            ->getToken($this->singer, $this->keyProvider->getPrivateKey());

        return $refreshToken;
    }
}