<?php

namespace App\AppUrlShortener\Interface;

interface ProviderInterface
{
    public function save(array $data): void;
}