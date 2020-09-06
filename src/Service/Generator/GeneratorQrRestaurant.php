<?php

namespace App\Service\Generator;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GeneratorQrRestaurant extends AbstractGeneratorQr
{
    private const FILE_FORMAT = '.png';

    private const SAVE_FOLDER = 'qr/';

    public function generate(string $slug, string $saveToPath): string
    {
        $slug = $this->urlGenerator->generate('get_restaurant', ['slug' => $slug], UrlGeneratorInterface::ABSOLUTE_URL);

        $this->QRCode->render($slug, self::SAVE_FOLDER . $saveToPath . self::FILE_FORMAT);

        return $saveToPath;
    }
}