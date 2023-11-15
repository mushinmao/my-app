<?php

namespace App\AppUrlShortener\Interface;

interface ConverterInterface
{
    public function convert(string $url): array;

    public function setShortLink(string $name): void;
}