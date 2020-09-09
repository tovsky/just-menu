<?php


namespace App\Security\Key;


use Lcobucci\JWT\Signer\Key;

interface KeyProviderInterface
{
    public function getPublicKey(): Key;

    public function getPrivateKey(): Key;
}
