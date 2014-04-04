<?php
/**
 * This file is part of the StarCollection project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection\Example;

/**
 * Class Color
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection\Example
 */
class Color
{
    const RED = 0;
    const YELLOW = 1;
    const BLUE = 2;
    const GREEN = 3;

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Returns the Value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
 