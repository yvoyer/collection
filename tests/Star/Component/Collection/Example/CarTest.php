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

    public function setUp()
    {
        $this->color = $this->getMockBuilder('tests\Star\Component\Collection\Example\Color')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testShouldHaveAColor()
    {
        $car = new Car('name', $this->color);
        $this->assertSame($this->color, $car->getColor());
    }

    public function testShouldHaveAName()
    {
        $car = new Car('name', $this->color);
        $this->assertSame('name', $car->getName());
    }
}
 