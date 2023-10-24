<?php

namespace App\Coder\Interface;
use App\UrlConverter\Interface\EncoderInterface;

interface CoderInterface extends EncoderInterface
{
    public function encode(string $string) : string;

}