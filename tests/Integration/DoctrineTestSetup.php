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
 * Class DoctrineTestSetup
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Integration
 */
class DoctrineTestSetup
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @return \Doctrine\ORM\Configuration
     */
    protected function getDoctrineConfiguration()
    {
        return Setup::createXMLMetadataConfiguration(array(__DIR__ . DIRECTORY_SEPARATOR . 'config'), true);
    }

    /**
     * @return array
     */
    protected function getDoctrineParameters()
    {
        return array(
            'driver' => 'pdo_sqlite',
            'in_memory' => true,
        );
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->connection) {
            $this->connection = DriverManager::getConnection($this->getDoctrineParameters());
        }

        if (null === $this->em || false === $this->em->isOpen()) {
            $this->em = EntityManager::create($this->connection, $this->getDoctrineConfiguration());
        }

        return $this->em;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->getEntityManager()->getConnection();
    }
}
 