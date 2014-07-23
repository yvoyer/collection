<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection\Example;

/**
 * Class Passenger
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection\Example
 */
class Passenger
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Car
     */
    private $car;

    /**
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
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
     * Returns the Name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the car.
     *
     * @param \tests\Star\Component\Collection\Example\Car $car
     */
    public function setCar($car)
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
 