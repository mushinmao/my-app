<?php

namespace Core\Container\Aggregator;

use InvalidArgumentException;
use Closure;

class ServiceAggregator
{
    protected bool $lock = false;
    protected array $calls = [];

    /**
     * @param string $name
     * @param string $class
     * @param array $arguments
     * @param array $calls
     * @param array $composition
     * @param Closure $compiler
     * @param array $tags
     */
    public function __construct
    (
        protected string $name,
        protected string $class,
        protected array $arguments,
        array $calls,
        protected array $composition,
        protected Closure $compiler,
        protected array $tags
    )
    {
        foreach ($calls as $call)
        {
            $this->calls[] = new ServiceMethods($this, $call['method'], $call['arguments'] ?? []);
        }
    }

    /**
     * @param string $name
     * @param array $serviceData
     * @return self
     */
    public static function createServiceObject(string $name, array $serviceData): self
    {
        if (!isset($serviceData['class'])) {
            throw new InvalidArgumentException("Service '{$name}' entry must be an array containing a '" . $serviceData['class'] . "' key");
        }

        return new static(
            $name,
            $serviceData['class'],
            $serviceData['arguments'] ?? [],
            $serviceData['calls'] ?? [],
            $serviceData['composition'] ?? [],
            $serviceData['compiler'] ?? function () {},
            $serviceData['tags'] ?? []
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return bool
     */
    public function hasTags(): bool
    {
        return !empty($this->tags);
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return bool
     */
    public function isLock(): bool
    {
        return $this->lock;
    }

    /**
     * @return void
     */
    public function lockService(): void
    {
        $this->lock = true;
    }

    /**
     * @return bool
     */
    public function hasCalls(): bool
    {
        return !empty($this->calls);
    }

    /**
     * @return ServiceMethods[]
     */
    public function getCalls(): array
    {
        return $this->calls;
    }

    /**
     * @return Closure
     */
    public function getCompiler(): Closure
    {
        return $this->compiler;
    }

    /**
     * @return bool
     */
    public function hasComposition(): bool
    {
        return !empty($this->composition);
    }

    /**
     * @return string
     */
    public function getCompositionParentClass(): string
    {
        return array_key_first($this->composition);
    }

    /**
     * @return string
     */
    public function getCompositionParentMethod(): string
    {
        return current($this->composition);
    }
}