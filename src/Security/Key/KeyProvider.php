<?php

namespace App\Security\Key;

use Lcobucci\JWT\Signer\Key;

class KeyProvider implements KeyProviderInterface
{
    private string $pathPublicKey;
    private string $pathPrivateKey;
    private string $passPhrase;

    public function __construct(
        string $passPhrase,
        string $pathPublicKey,
        string $pathPrivateKey
    ) {
        $this->pathPublicKey = $pathPublicKey;
        $this->pathPrivateKey = $pathPrivateKey;
        $this->passPhrase = $passPhrase;
    }

    public function getPublicKey(): Key
    {
       return new Key($this->pathPublicKey);
    }

    public function getPrivateKey(): Key
    {
       return new Key($this->pathPrivateKey, $this->passPhrase);
    }
}
