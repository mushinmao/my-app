<?php

namespace App\AppUrlShortener;

use App\AppUrlShortener\Interface\ConverterInterface;
use App\AppUrlShortener\Interface\ProviderInterface;

class Shortener
{
    public function __construct(protected ProviderInterface $provider, protected ConverterInterface $converter)
    {}

    /**
     * @param string $url
     * @return void
     */
    public function convert(string $url): void
    {
        $this->provider->save($this->converter->convert($url));
    }

    /**
     * @param string $name
     * @return void
     */
    public function setShortLink(string $name): void
    {
        $this->converter->setShortLink($name);
    }
}