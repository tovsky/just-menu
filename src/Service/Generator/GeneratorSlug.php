<?php

namespace App\Service\Generator;

use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\SluggerInterface;

class GeneratorSlug
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function generate(string $name): AbstractUnicodeString
    {
        return $this->slugger->slug($name);
    }
}