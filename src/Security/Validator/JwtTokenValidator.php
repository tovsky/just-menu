<?php

namespace App\Security\Validator;

use App\Security\Key\KeyProviderInterface;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Parser;

class JwtTokenValidator implements JwtTokenValidatorInterface
{
    private KeyProviderInterface $keyProvider;

    private Rsa\Sha256 $singer;

    private Parser $parser;

    public function __construct(
        KeyProviderInterface $keyProvider,
        Rsa\Sha256 $singer,
        Parser $parser
    ) {
        $this->keyProvider = $keyProvider;
        $this->singer = $singer;
        $this->parser = $parser;
    }

    /**
     * Возвращает true если срок действия JWT истек
     *
     * @param string $tokenAsString
     * @return bool
     */
    public function isTokenExpired(string $tokenAsString): bool
    {
        $token = $this->parser->parse($tokenAsString);

        return $token->isExpired();
    }

    /**
     * Возвращает true если подпись jwt валидна
     *
     * @param string $tokenAsString
     * @return bool
     */
    public function isValidSignature(string $tokenAsString): bool
    {
        if ($this->isNotCorrectFormat($tokenAsString)) {
            return false;
        }

        $token = $this->parser->parse($tokenAsString);
        return $token->verify($this->singer, $this->keyProvider->getPublicKey());
    }

    /**
     * Проверяет что JSON будет содержать два символа '.'
     *
     * @param string $token
     * @return bool
     */
    private function isNotCorrectFormat(string $token): bool
    {
        return substr_count($token, '.') !== 2;
    }
}
