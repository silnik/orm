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
        if (
            !empty(getenv('DB_USERNAME')) &&
            !empty(getenv('DB_USERNAME')) &&
            !empty(getenv('DB_USERNAME'))
        ) {
            $this->connection = [
                'driver' => getenv('DB_DRIVER') ?? 'pdo_mysql',
                'host' => getenv('DB_HOST') ?? 'localhost',
                'port' => getenv('DB_PORT') ?? 3306,
                'user' => getenv('DB_USERNAME'),
                'password' => getenv('DB_PASSWORD'),
                'dbname' => getenv('DB_DATABASE'),
                'charset' => getenv('DB_CHARSET'),
            ];
        } else {
            $this->connection = [
                'path' => $_ENV['PATH_DATABASE'] . 'db.sqlite',
            ];
        }
        $isDevMode = (getenv('APP_ENV') != 'production');

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
