<?php

namespace Core\Container\Aggregator;

class ServiceMethods
{
    public function __construct
    (
        protected ServiceAggregator $serviceObject,
        protected string            $method,
        protected array             $arguments
    )
    {}

    /**
     * @return ServiceAggregator
     */
    public function getServiceObject(): ServiceAggregator
    {
        return $this->serviceObject;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments ?? [];
    }
}