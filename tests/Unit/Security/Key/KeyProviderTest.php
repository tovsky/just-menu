<?php

namespace App\Tests\Unit\Security\Key;

use App\Security\Key\KeyProviderInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class KeyProviderTest extends KernelTestCase
{
    private KeyProviderInterface $keyProvider;

    protected function setUp()
    {
        self::bootKernel();
        $this->keyProvider = self::$container->get(KeyProviderInterface::class);
    }

    public function testGetPublicKey()
    {
        $publicKey = $this->keyProvider->getPublicKey();

        $this->assertStringContainsString('-----END PUBLIC KEY-----', $publicKey->getContent());
    }

    public function testGetPrivateKey()
    {
        $publicKey = $this->keyProvider->getPrivateKey();

        $this->assertStringContainsString('-----END RSA PRIVATE KEY-----', $publicKey->getContent());
    }
}
