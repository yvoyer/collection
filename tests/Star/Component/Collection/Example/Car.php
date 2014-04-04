<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection\Example;

/**
 * Class Car
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection\Example
 */
class Car
{
    private $color;
    private $name;

    public function __construct($name, Color $color)
    {
        $this->name = $name;
        $this->color = $color;
    }

    public function getColor()
    {
        return $this->color;
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
}
 