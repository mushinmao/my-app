<?php

namespace App\UrlConverter;

use App\Randomizer\Interface\RandomizerInterface;
use App\UrlConverter\Interface\EncoderInterface;
use App\UrlConverter\Interface\ValidatorInterface;
use App\UrlValidator\Exception\InvalidUrlException;
use App\UrlValidator\Exception\InvalidUrlStatusException;

class UrlConverter
{
    protected string $shortLink;

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
     * @return array
     */
    public function convert(string $url): array
    {
        try {
            $this->validator->validate($url);
        }

        catch (InvalidUrlException|InvalidUrlStatusException $e){
            echo $e->getMessage();
        }

        $shortUrl = $this->shortLink ?? $this->randomizer->randomize();

        $hash = $this->coder->encode($url);

        return ['hash' => $hash, 'url' => $url, 'code' => $shortUrl];
    }

    /**
     * @param string $name
     * @return void
     */
    public function setShortLink(string $name): void
    {
        $this->shortLink = $name;
    }

    public function setShortLinkLength(int $length): void
    {
        $this->randomizer->setLength($length);
    }
}