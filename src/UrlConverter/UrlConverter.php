<?php

namespace App\UrlConverter;

use App\Randomizer\Interface\RandomizerInterface;
use App\UrlConverter\Interface\EncoderInterface;
use App\UrlConverter\Interface\ValidatorInterface;
use App\UrlValidator\Exception\InvalidUrlException;
use App\UrlValidator\Exception\InvalidUrlStatusException;

class UrlConverter
{
    /**
     * @param EncoderInterface $coder
     * @param RandomizerInterface $randomizer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        protected EncoderInterface $coder,
        protected RandomizerInterface $randomizer,
        protected ValidatorInterface $validator
    )
    {}

    /**
     * @param string $url
     * @param string|null $concreteShortUrl
     * @return array
     */
    public function createUrlData(string $url, ?string $concreteShortUrl = null) : array
    {
        try {
            $this->validator->validate($url);
        }

        catch (InvalidUrlException|InvalidUrlStatusException $e){
            echo $e->getMessage();
        }

        $shortUrl = $concreteShortUrl ?? $this->randomizer->randomize();

        $code = $this->coder->encode($url);

        return ['code' => $code, 'url' => $url, 'short' => $shortUrl];
    }
}