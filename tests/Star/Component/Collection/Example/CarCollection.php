<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection\Example;

use Doctrine\Common\Collections\Criteria;
use Star\Component\Collection\TypedCollection;

/**
 * Class BlogCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection\Example
 */
class CarCollection
{
    private $collection;

    public function __construct()
    {
        $this->collection = new TypedCollection('tests\Star\Component\Collection\Example\Car');
    }

    public function addCar(Car $car)
    {
        $this->collection->add($car);
    }

    public function findAllCarWithColor(Color $color)
    {
        $closure = function(Car $car) use ($color) {
            return $car->getColor() == $color;
        };

        return $this->collection->filter($closure)->toArray();
    }

    public function findAllWithName($name)
    {
        $expression = Criteria::expr()->eq('name', $name);
        $criteria = new Criteria($expression);

        return $this->collection->matching($criteria)->toArray();
    }
}
 