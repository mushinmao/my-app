<?php

namespace App\UrlValidator;

use App\UrlValidator\Exception\InvalidUrlException;
use App\UrlValidator\Exception\InvalidUrlStatusException;
use App\UrlValidator\Interface\UrlValidatorInterface;

class UrlValidator implements UrlValidatorInterface
{
    protected string $error;
    private mixed $httpCode;

    /**
     * @param string $url
     * @return bool
     * @throws InvalidUrlException
     * @throws InvalidUrlStatusException
     */
    public function validate(string $url): bool
    {
        $this->execute($url);

        if (!empty($this->getError())) {
            throw new InvalidUrlException($this->getError());
        }

        return match ($this->getHttpCode()) {
            301, 200 => true,
            default => throw new InvalidUrlStatusException("Url $url status $this->httpCode")
        };
    }

    /**
     * @param string $url
     * @return void
     */
    protected function execute(string $url): void
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);

        curl_exec($ch);

        $error       = curl_error($ch);
        $http_code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $this->error = $error;
        $this->httpCode = $http_code;
    }

    /**
     * @return mixed
     */
    protected function getHttpCode(): mixed
    {
        return $this->httpCode;
    }

    /**
     * @return string
     */
    protected function getError(): string
    {
        return strip_tags($this->error);
    }


}