<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection\Example;

use Star\Component\Collection\TypedCollection;

/**
 * Class PassengerCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection\Example
 */
class PassengerCollection extends TypedCollection
{
    public function __construct(array $elements = array())
    {
        parent::__construct(__NAMESPACE__ . '\Passenger', $elements);
    }
}
 