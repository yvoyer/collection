<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection\Example;

/**
 * Class Wheel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection\Example
 */
class Wheel
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var
     */
    private $id;

    /**
     * @var integer
     */
    private $size;

    /**
     * @var Car
     */
    private $car;

    /**
     * @param integer $size
     */
    public function __construct($size)
    {
        $this->size = $size;
    }

    /**
     * Returns the Id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the Size.
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the car.
     *
     * @param \tests\Star\Component\Collection\Example\Car $car
     */
    public function setCar(Car $car)
    {
        $this->car = $car;
    }

    /**
     * Returns the Car.
     *
     * @return \tests\Star\Component\Collection\Example\Car
     */
    public function getCar()
    {
        return $this->car;
    }
}
 