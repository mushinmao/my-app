<?php

namespace App\UrlConverter\Interface;

use App\UrlValidator\Exception\InvalidUrlException;
use App\UrlValidator\Exception\InvalidUrlStatusException;

interface ValidatorInterface
{
    /**
     * @param string $url
     * @return bool
     * @throws InvalidUrlException|InvalidUrlStatusException
     */
    public function validate(string $url) : bool;
}