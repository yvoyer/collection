<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Integration;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Tester\CommandTester;
use tests\Star\Component\Collection\Example\Car;
use tests\Star\Component\Collection\Example\Color;
use tests\Star\Component\Collection\Example\Wheel;

/**
 * Class DoctrineTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Integration
 */
class DoctrineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DoctrineTestSetup
     */
    private static $doctrine;

    public static function setUpBeforeClass()
    {
        self::$doctrine = new DoctrineTestSetup();
        $em = self::$doctrine->getEntityManager();

        $classes = array(
            $em->getClassMetadata(Car::CLASS_NAME),
            $em->getClassMetadata(Wheel::CLASS_NAME),
        );

        $tool = new SchemaTool($em);
        $tool->dropSchema($classes);
        $tool->createSchema($classes);
    }

    public function test_should_persists_car()
    {
        $em = self::$doctrine->getEntityManager();

        $newCar = new Car('name', Color::getBlue());
        $em->persist($newCar);
        $em->flush();
        $em->clear();

        $car = $this->findCar($newCar->getId());

        $this->assertSame('name', $car->getName());
        $this->assertEquals(Color::getBlue(), $car->getColor());

        return $car;
    }

    /**
     * @param Car $car
     *
     * @depends test_should_persists_car
     */
    public function testShouldPersistWheels(Car $car)
    {
        $car->addWheel(12);
        $car->addWheel(12);
        $car->addWheel(12);
        $car->addWheel(12);

        $em = self::$doctrine->getEntityManager();
        $em->persist($car);
        $em->flush();
        $em->clear();
        $newCar = $this->findCar($car->getId());

        $this->assertCount(4, $newCar->getWheels());
        $this->assertInstanceOf('Doctrine\ORM\PersistentCollection', $newCar->getWheels());
    }

    /**
     * @param $carId
     *
     * @return Car
     */
    private function findCar($carId)
    {
        /**
         * @var $car Car
         */
        $car = self::$doctrine->getEntityManager()->getRepository(Car::CLASS_NAME)->find($carId);

        return $car;
    }
}
 