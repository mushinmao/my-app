<?php

namespace Config;

use Core\Container\Interface\ContainerInterface;
use Core\Exceptions\ContainerException;
use Core\Exceptions\ParameterNotFoundException;
use Core\Interface\SingletonInterface;
use Core\Trait\Singletonable;

class Config implements ContainerInterface, SingletonInterface
{
    protected array $config = [];

    use Singletonable;

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        try {
            $result = true;

            $this->getRealPath($id);
        }

        catch (ParameterNotFoundException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ParameterNotFoundException
     */
    public function get(string $id): mixed
    {
        return $this->getRealPath($id);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws ContainerException
     * @throws ParameterNotFoundException
     */
    public function __get(string $name)
    {
        return $this->get(str_replace('_', '.', $name));
    }

    /**
     * @param array $configs
     * @return $this
     */
    public function addConfigs(array $configs) : self
    {
        $this->config = array_merge($this->config, $configs);

        return $this;
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ParameterNotFoundException
     */
    protected function getRealPath(string $id): mixed
    {
        $tokens = explode('.', $id);

        $context = $this->config;

        while (null !== ($token = array_shift($tokens))) {
            if (!isset($context[$token])) {
                throw new ParameterNotFoundException('Parameter not found: ' . $id);
            }

            $context = $context[$token];
        }

        return $context;
    }
}