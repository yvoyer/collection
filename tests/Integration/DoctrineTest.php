<?php
/**
 * This file is part of the StarCollection project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Integration;

use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use tests\Star\Component\Collection\Example\Car;
use tests\Star\Component\Collection\Example\Color;
use tests\Star\Component\Collection\Example\Passenger;
use tests\Star\Component\Collection\Example\Wheel;

/**
 * Class DoctrineTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Integration
 */
class DoctrineTest extends TestCase
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
            $em->getClassMetadata(Passenger::CLASS_NAME),
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

    public function testShouldPersistPassenger()
    {
        $passenger = new Passenger(-1, 'my-name');
        $em = self::$doctrine->getEntityManager();
        $em->persist($passenger);
        $em->flush();
        $em->clear();
        $newPassenger = $this->findPassenger(1);

        $this->assertInstanceOf(Passenger::CLASS_NAME, $newPassenger);
        $this->assertSame('my-name', $newPassenger->getName());
        $this->assertSame(1, $newPassenger->getId());
    }

    public function testShouldPersistTheCollectionOfPassenger()
    {
        $car = $this->findCar(1);
        $passenger = $this->findPassenger(1);

        $this->assertInstanceOf(Car::CLASS_NAME, $car);
        $this->assertInstanceOf(Passenger::CLASS_NAME, $passenger);

        $car->embark($passenger);
        $this->assertCount(1, $car->getPassengers());

        $em = self::$doctrine->getEntityManager();
        $em->persist($car);
        $em->flush();
        $em->clear();

        $refreshCar = $this->findCar(1);
        $this->assertInstanceOf(Car::CLASS_NAME, $refreshCar);
        $this->assertInstanceOf('tests\Star\Component\Collection\Example\PassengerCollection', $refreshCar->getPassengers());
        $this->assertCount(1, $refreshCar->getPassengers());
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

    /**
     * @param int $id
     *
     * @return Passenger
     */
    private function findPassenger($id)
    {
        return self::$doctrine->getEntityManager()->getRepository(Passenger::CLASS_NAME)->find($id);
    }
}
