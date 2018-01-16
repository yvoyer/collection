<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection\Example;

use PHPUnit\Framework\TestCase;

/**
 * Class CarCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection\Example
 */
class CarCollectionTest extends TestCase
{
    /**
     * @var CarCollection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new CarCollection();
        $this->collection->addCar(new Car('blue-car', new Color(Color::BLUE)));
        $this->collection->addCar(new Car('green-car', new Color(Color::GREEN)));
        $this->collection->addCar(new Car('red-car', new Color(Color::RED)));
        $this->collection->addCar(new Car('yellow-car', new Color(Color::YELLOW)));
    }

    public function testShouldAddTheCars()
    {
        $this->assertAttributeCount(4, 'collection', $this->collection);
    }

    public function testShouldReturnTheRedCar()
    {
        $color = new Color(Color::RED);
        $aElements = $this->collection->findAllCarWithColor($color);

        $this->assertCount(1, $aElements);
        $car = array_shift($aElements);
        $this->assertEquals($color, $car->getColor());
    }

    public function testShouldReturnTheGreenCar()
    {
        $aElements = $this->collection->findAllWithName('green-car');

        $this->assertCount(1, $aElements);
        $car = array_shift($aElements);
        $this->assertEquals('green-car', $car->getName());
    }
}
