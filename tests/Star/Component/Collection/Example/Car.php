<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection\Example;

use Doctrine\Common\Collections\ArrayCollection;
use Star\Component\Collection\TypedCollection;

/**
 * Class Car
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection\Example
 */
class Car
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var Color
     */
    private $color;

    /**
     * @var string
     */
    private $name;

    /**
     * @var TypedCollection
     */
    private $wheels;

    /**
     * @var PassengerCollection
     */
    private $passengers;

    /**
     * @param string $name
     * @param Color  $color
     */
    public function __construct($name, Color $color)
    {
        $this->name = $name;
        $this->color = (string) $color;
        $this->wheels = new TypedCollection(Wheel::CLASS_NAME);
        $this->passengers = new PassengerCollection();
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
     * @return Color
     */
    public function getColor()
    {
        return new Color($this->color);
    }

    /**
     * @param $size
     */
    public function addWheel($size)
    {
        $wheel = new Wheel($size);
        $wheel->setCar($this);

        $this->wheels->add($wheel);
    }

    /**
     * @return TypedCollection
     */
    public function getWheels()
    {
        return $this->wheels;
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
     * @param Passenger $passenger
     */
    public function embark(Passenger $passenger)
    {
        $passenger->setCar($this);
        $this->passengers->add($passenger);
    }

    /**
     * Returns the Passengers.
     *
     * @return \tests\Star\Component\Collection\Example\PassengerCollection
     */
    public function getPassengers()
    {
        return new PassengerCollection($this->passengers->toArray());
    }
}
 