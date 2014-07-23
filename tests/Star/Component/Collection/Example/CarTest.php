<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection\Example;

/**
 * Class CatTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection\Example
 */
class CarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Color
     */
    private $color;

    /**
     * @var Car
     */
    private $car;

    /**
     * @var Passenger
     */
    private $passenger;

    public function setUp()
    {
        $this->color = new Color(1);
        $this->car = new Car('name', $this->color);
        $this->passenger = new Passenger(2, 'driver');
    }

    public function testShouldHaveAColor()
    {
        $this->assertEquals($this->color, $this->car->getColor());
    }

    public function testShouldHaveAName()
    {
        $this->assertSame('name', $this->car->getName());
    }

    public function testShouldHavePassengers()
    {
        $this->assertInstanceOf(__NAMESPACE__ . '\PassengerCollection' , $this->car->getPassengers());
        $this->assertCount(0, $this->car->getPassengers());
        $this->car->embark($this->passenger);
        $this->assertCount(1, $this->car->getPassengers());
    }
}
 