<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Integration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

/**
 * Class DoctrineSetup
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Integration
 */
trait DoctrineSetup
{
    /**
     * @var EntityManager
     */
    private static $em;

    /**
     * @var Connection
     */
    private static $connection;

    /**
     * @return \Doctrine\ORM\Configuration
     */
    protected static function getDoctrineConfiguration()
    {
        return Setup::createXMLMetadataConfiguration(array(__DIR__ . DIRECTORY_SEPARATOR . 'config'), true);
    }

    /**
     * @return array
     */
    protected static function getDoctrineParameters()
    {
        return array(
            'driver' => 'pdo_sqlite',
            'in_memory' => true,
        );
    }

    /**
     * @return EntityManager
     */
    protected static function getEntityManagerForClass()
    {
        if (null === self::$connection) {
            self::$connection = DriverManager::getConnection(self::getDoctrineParameters());
        }

        if (null === self::$em || false === self::$em->isOpen()) {
            self::$em = EntityManager::create(self::$connection, self::getDoctrineConfiguration());
        }

        return self::$em;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return self::getEntityManagerForClass();
    }

    /**
     * @return Connection
     */
    protected function getDoctrineConnection()
    {
        return $this->getEntityManager()->getConnection();
    }
}
 