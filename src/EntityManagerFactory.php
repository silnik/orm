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
        $pathEntity = [dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'entity'];

        $config = ORMSetup::createAttributeMetadataConfiguration($pathEntity, $isDevMode, null, null);
        //$config->setProxyDir(dirname(__DIR__,2).'/storage/proxyTemp');

        return new EntityManager(
            DriverManager::getConnection($this->connection, $config),
            $config
        );
    }
}
