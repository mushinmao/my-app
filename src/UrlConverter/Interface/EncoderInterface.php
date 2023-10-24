<?php

namespace App\UrlConverter\Interface;

interface EncoderInterface
{
    public function encode(string $string) : string;

}