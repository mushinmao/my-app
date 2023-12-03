<?php

namespace App\Database\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;

class DoctrineORM
{
    const DRIVER = 'pdo_mysql';
    const HOST = 'localhost';

    protected ?EntityManager $em = null;
    protected Configuration $config;
    protected Connection $connection;

    /**
     * @throws Exception
     */
    public function __construct(
        string $database,
        string $user,
        string $pass,
        string $host = self::HOST,
        bool   $isDevMode = false,
        string $dbDriver = self::DRIVER,
        array  $entityPaths = [],
    )
    {
        if (empty($entityPaths)) {
            $entityPaths = [
                __DIR__ . '/Entity'
            ];
        }

        $dbParams = [
            'host' => $host,
            'driver' => $dbDriver,
            'user' => $user,
            'password' => $pass,
            'dbname' => $database,
        ];

        $this->config = ORMSetup::createAttributeMetadataConfiguration($entityPaths, $isDevMode);
        $this->connection = DriverManager::getConnection($dbParams, $this->config);
    }

    /**
     * @return EntityManager
     * @throws MissingMappingDriverImplementation
     */
    public function getEM(): EntityManager
    {
        if (is_null($this->em)) {
            $this->em = new EntityManager($this->connection, $this->config);
        }
        return $this->em;
    }
}