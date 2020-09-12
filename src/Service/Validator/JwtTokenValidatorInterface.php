<?php


namespace App\Service\Validator;


interface JwtTokenValidatorInterface
{
    public function isValidSignature(string $token): bool;

    public function isTokenExpired(string $token): bool;
}
