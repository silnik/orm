<?php

namespace Silnik\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;

class EntityManagerFactory
{
    private $connection = [];

    public function getEntityManager(): EntityManagerInterface
    {
        if (isset($_ENV['DB_USERNAME']) && isset($_ENV['DB_PASSWORD']) && isset($_ENV['DB_DATABASE']) &&
            !empty($_ENV['DB_USERNAME']) && !empty($_ENV['DB_PASSWORD']) && !empty($_ENV['DB_DATABASE'])
        ) {
            $this->connection = [
                'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
                'host' => $_ENV['DB_HOST'] ?? 'localhost',
                'port' => $_ENV['DB_PORT'] ?? 3306,
                'user' => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD'],
                'dbname' => $_ENV['DB_DATABASE'],
                'charset' => $_ENV['DB_CHARSET'] ?? 'utf8',
            ];
        } else {
            $this->connection = [
                'path' => $_ENV['PATH_DATABASE'] . 'db.sqlite',
            ];
        }
        $isDevMode = true;

        $config = ORMSetup::createAttributeMetadataConfiguration(
            [dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Entity'],
            $isDevMode,
            null,
            null
        );
        $config->setProxyDir(dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'DoctrineProxies');

        return new EntityManager(
            DriverManager::getConnection($this->connection, $config),
            $config
        );
    }
}
