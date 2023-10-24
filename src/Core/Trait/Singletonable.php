<?php

namespace Core\Trait;

use Exception;

trait Singletonable
{
    protected static ?self $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function closeMethod(): void
    {
        throw new Exception('This class is singleton');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function __clone(): void
    {
        $this->closeMethod();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function __wakeup(): void
    {
        $this->closeMethod();
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function __unserialize(array $data): void
    {
        $this->closeMethod();
    }

}