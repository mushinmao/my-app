<?php

namespace Core\Container;


use Closure;
use Core\Container\Aggregator\ServiceAggregator;
use Core\Container\Enum\MethodTypes;
use Core\Container\Interface\ContainerInterface;
use Core\Exceptions\ContainerException;
use Core\Exceptions\ParameterNotFoundException;
use Core\Exceptions\ServiceClassUndefinedException;
use Core\Exceptions\ServiceNotFoundException;
use ReflectionClass;
use ReflectionException;

class Container implements ContainerInterface
{
    /**
     * @var ServiceAggregator[] $serviceStorage
     */
    protected array $serviceStorage;
    protected array $compiled;
    protected array $tags;


    public function __construct(array $services, protected ContainerInterface $configStorage)
    {
        $this->storeServices($services);
        $this->checkTagsList();
    }

    /**
     * @param string $id
     * @return object
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ServiceClassUndefinedException
     * @throws ServiceNotFoundException
     */
    public function get(string $id): object
    {
        if (!$this->has($id)) {
            throw new ServiceNotFoundException("Service $id not found");
        }

        return $this->compile($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->serviceStorage[$id]);
    }

    /**
     * @param string $id
     * @return array
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ServiceClassUndefinedException
     * @throws ServiceNotFoundException
     */
    public function getByTag (string $id): array
    {
        if (!isset($this->tagsStore[$id])) {
            throw new ServiceNotFoundException('Tag not found: ' . $id);
        }

        $services = [];
        foreach ($this->tagsStore[$id] as $serviceName) {
            $services[] = $this->get($serviceName);
        }

        return $services;
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ContainerException
     * @throws ParameterNotFoundException
     */
    public function getParam(string $id): mixed
    {
        return $this->configStorage->get($id);
    }

    /**
     * @param array $services
     * @return $this
     */
    protected function storeServices(array $services): self
    {
        foreach ($services as $name => $data) {
            $this->serviceStorage[$name] = ServiceAggregator::createServiceObject($name, $data);
        }

        return $this;
    }

    /**
     * @param string $name
     * @return object
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ServiceClassUndefinedException
     */
    protected function compile(string $name): object
    {
        $entity = $this->serviceStorage[$name];

        if (!class_exists($entity->getClass())) {
            throw new ServiceClassUndefinedException($name . ' service class does not exist: ' . $entity->getClass());
        }

        elseif ($entity->isLock()) {
            throw new ContainerException($name . ' service contains a circular reference');
        }

        $entity->lockService();

        $arguments = $this->resolveArguments($entity->getArguments());

        if ($entity->hasComposition()) {
            $service = $this->compileObject($entity->getCompositionParentClass(), $arguments, $entity->getCompositionParentMethod());
        }

        else {
            $service = $this->compileObject($entity->getClass(), $arguments);
        }

        if ($entity->hasCalls()) {
            $this->executeCall($service, $entity);
        }

        $this->executeClosure($service, $entity, $entity->getCompiler());

        $this->compiled[$name] = $service;

        return $service;
    }

    /**
     * @param string $class_name
     * @param array $arguments
     * @param ?string $method
     * @return object
     * @throws ReflectionException
     */
    protected function compileObject(string $class_name, array $arguments = [], string $method = null) : object
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

            $arguments = $this->resolveArguments($call->getArguments());

            call_user_func_array([$service, $call->getMethod()], $arguments);
        }
    }

    /**
     * @param object $service
     * @param ServiceAggregator $entity
     * @param Closure $closure
     * @return void
     * @throws ContainerException
     */
    protected function executeClosure(object $service, ServiceAggregator $entity, Closure $closure): void
    {
        try {
            $closure($this, $service, $entity);
        }

        catch (\Exception $e) {
            throw new ContainerException('Container compiler error: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param array $args
     * @return array
     */
    protected function resolveArguments(array $args): array
    {
       $resolved = [];

        foreach ($args as $arg) {
            if (empty($arg)) {
                continue;
            }

            $resolved[] = $this->resolveArgType($arg);
        }

        return $resolved;
    }

    /**
     * @param mixed $argument
     * @return mixed
     */
    protected function resolveArgType(mixed $argument): mixed
    {
        $method = MethodTypes::getMethodByType(substr($argument, 0,1))->value;

        $arg = substr($argument, 1);

        return $this->{$method}($arg);
    }

    /**
     * @return void
     */
    protected function checkTagsList(): void
    {
        foreach ($this->serviceStorage as $service) {
            if ($service->hasTags()) {
                $this->addTagsToList($service->getName(), $service->getTags());
            }
        }
    }

    /**
     * @param string $id
     * @param array $tags
     * @return void
     */
    protected function addTagsToList(string $id, array $tags): void
    {
        foreach ($tags as $tag) {
            $this->tags[$tag][] = $id;
        }
    }
}