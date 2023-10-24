<?php

namespace Core\Container;

use Closure;
use Core\Container\Aggregator\ServiceAggregator;
use Core\Container\Interface\ContainerInterface;
use Core\Exceptions\ContainerException;
use Core\Exceptions\NotFoundException;
use Core\Exceptions\ParameterNotFoundException;
use Core\Exceptions\ServiceNotFoundException;
use ReflectionClass;
use ReflectionException;

class Container implements ContainerInterface
{
    protected array $services;
    protected array $readyServices;
    protected array $tags;

    public function __construct(array $services, protected ContainerInterface $parameters)
    {
        $this->addServiceObjects($services);
        $this->checkTagsList();
    }

    /**
     * @param string $id
     * @return object
     * @throws ContainerException
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     */
    public function get(string $id): object
    {
        try {
            $result = $this->parameters->get($id);
        }

        catch (ParameterNotFoundException) {
            if (!$this->has($id)) {
                throw new NotFoundException("Service $id not found");
            }

            $result = $this->buildService($id);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * @param string $id
     * @return array
     * @throws ContainerException
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     */
    public function getByTag(string $id): array
    {
        if (!isset($this->tags[$id])) {
            throw new ServiceNotFoundException('Tag not found: ' . $id);
        }

        $services = [];
        foreach ($this->tags[$id] as $serviceName) {
            $services[] = $this->get($serviceName);
        }

        return $services;
    }

    /**
     * @param array $services
     * @return $this
     */
    protected function addServiceObjects(array $services): self
    {
        foreach ($services as $name => $data) {
            $this->services[$name] = ServiceAggregator::createServiceObject($name, $data);
        }

        return $this;
    }

    /**
     * @param string $name
     * @return object
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ServiceNotFoundException
     */
    protected function buildService(string $name): object
    {
        /**
         * @var ServiceAggregator $entity
         */
        $entity = $this->services[$name];

        if (!class_exists($entity->getClass())) {
            throw new ServiceNotFoundException($name . ' service class does not exist: ' . $entity->getClass());
        }

        elseif ($entity->isLock()) {
            throw new ContainerException($name . ' service contains a circular reference');
        }

        $entity->lockService();

        $arguments = $this->resolveArguments($entity->getArguments());

        if ($entity->hasComposition()) {
            $service = $this->createObjectFromReflection($entity->getCompositionParentClass(), $arguments, $entity->getCompositionParentMethod());
        }

        else {
            $service = $this->createObjectFromReflection($entity->getClass(), $arguments);
        }

        if ($entity->hasCalls()) {
                $this->executeCall($service, $entity);
        }

        $this->executeCompiler($service, $entity, $entity->getCompiler());

        $this->readyServices[$name] = $service;

        return $service;
    }

    /**
     * @param array $args
     * @return array
     * @throws ReflectionException
     */
    protected function resolveArguments(array $args) : array
    {
       $resolved = [];

        foreach ($args as $arg) {
            if (!$this->has($arg)) {
                $resolved[] = $arg;
            }

            else {
                try {
                    $resolved[] = $this->buildService($arg);
                }

                catch (ContainerException) {
                    $resolved[] = $this->readyServices[$arg];
                }
            }
        }

        return $resolved;
    }
    /**
     * @param object $service
     * @param ServiceAggregator $entity
     * @return void
     * @throws ContainerException
     */
    protected function executeCall(object $service, ServiceAggregator $entity): void
    {
        foreach ($entity->getCalls() as $call) {

            if (!is_callable([$service, $call->getMethod()])) {
                throw new ContainerException($entity->getName() . ' service asks for call to uncallable method: ' . $call->getMethod());
            }

            $arguments = $call->getArguments();

            call_user_func_array([$service, $call->getMethod()], $arguments);
        }
    }

    /**
     * @param object $service
     * @param ServiceAggregator $entity
     * @param Closure $compiler
     * @return void
     * @throws ContainerException
     */
    protected function executeCompiler(object $service, ServiceAggregator $entity, Closure $compiler): void
    {
        try {
            $compiler($this, $service, $entity);
        }

        catch (\Exception $e) {
            throw new ContainerException('Container compiler error: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $class_name
     * @param array $arguments
     * @param string $method
     * @return object
     * @throws ReflectionException
     */
    protected function createObjectFromReflection(string $class_name, array $arguments = [], string $method = '') : object
    {
        $class = new ReflectionClass($class_name);

        if (!empty($method)) {
            $object = $class->getMethod($method)->invoke($class->newInstanceArgs($arguments));
        }

        else {
            $object = $class->newInstanceArgs($arguments);
        }

        return $object;
    }

    /**
     * @return void
     */
    protected function checkTagsList(): void
    {
        /**
         * @var ServiceAggregator $service
         */
        foreach ($this->services as $service) {
            if ($service->hasTags()) {
                $this->addTagsToList($service->getName(), $service->getTags());
            }
        }
    }

    /**
     * @param string $id
     * @param array $tags
     */
    protected function addTagsToList(string $id, array $tags): void
    {
        foreach ($tags as $tag) {
            $this->tags[$tag][] = $id;
        }
    }
}