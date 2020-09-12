<?php

namespace App\Service\Generator;

use chillerlan\QRCode\QRCode;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractGeneratorQr
{
    protected QRCode $QRCode;

    protected UrlGeneratorInterface $urlGenerator;

    public function __construct(QRCode $QRCode, UrlGeneratorInterface $urlGenerator)
    {
        $this->QRCode = $QRCode;
        $this->urlGenerator = $urlGenerator;
    }

    abstract public function generate(array $keys, string $saveToPath): string;
}