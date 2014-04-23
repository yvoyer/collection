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
    const CLASS_NAME = __CLASS__;

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

    public function __toString()
    {
        return strval($this->value);
    }

    /**
     * @return Color
     */
    public static function getRed()
    {
        return new Color(self::RED);
    }

    /**
     * @return Color
     */
    public static function getBlue()
    {
        return new Color(self::BLUE);
    }

    /**
     * @return Color
     */
    public static function getGreen()
    {
        return new Color(self::GREEN);
    }

    /**
     * @return Color
     */
    public static function getYellow()
    {
        return new Color(self::YELLOW);
    }
}
 