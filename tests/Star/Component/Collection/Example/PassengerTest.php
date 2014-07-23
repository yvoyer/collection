<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection\Example;

/**
 * Class PassengerTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection\Example
 */
class PassengerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Passenger
     */
    private $passenger;

    public function setUp()
    {
        $this->passenger = new Passenger(2, 'name');
    }

    public function testShouldReturnTheId()
    {
        $this->assertSame(2, $this->passenger->getId());
    }

    public function testShouldReturnTheName()
    {
        $this->assertSame('name', $this->passenger->getName());
    }

    public function testShouldReturnTheCar()
    {
        $car = $this->getMock(Car::CLASS_NAME, array(), array(), '', false);
        $this->assertNull($this->passenger->getCar());
        $this->passenger->setCar($car);
        $this->assertSame($car, $this->passenger->getCar());
    }
}
 