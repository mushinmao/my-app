<?php
namespace App\Coder;

use App\Coder\Exception\DecodeException;
use App\Coder\Interface\CoderInterface;

class StringCoder implements CoderInterface
{

    /**
     * @param string $string
     * @return string
     * @throws DecodeException
     */
    public function decode(string $string): string
    {
        $code = base64_decode($string, true);

        if (!$code) {
            throw new DecodeException('Invalid encoded string');
        }

        return $code;
    }

    /**
     * @param string $string
     * @return string
     */
    public function encode(string $string): string
    {
        return trim(base64_encode($string));
    }
}