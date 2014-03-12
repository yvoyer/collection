<?php
/**
 * This file is part of the collection.local project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Collection;

use Star\Component\Collection\Exception\InvalidArgumentException;
use Star\Component\Collection\Exception\UnsupportedValueException;
use Traversable;

/**
 * Class Enumeration
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Collection
 *
 * @since 1.0.0
 */
class Enumeration implements \Countable, \IteratorAggregate
{
    /**
     * @var array
     */
    private $supportedValues;

    /**
     * @var mixed
     */
    private $selected;

    /**
     * Builds the enumeration with the given $values.
     * Values must be of type numeric or strings.
     *
     * @param array $values
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(array $values)
    {
        if (empty($values)) {
            throw new InvalidArgumentException('Values should be given.');
        }

        foreach ($values as $value) {
            $this->assertValueIsValid($value);
        }

        $this->supportedValues = $values;
    }

    /**
     * @param mixed $element
     * @throws Exception\UnsupportedValueException
     */
    public function select($element)
    {
        $this->assertSupportedElement($element);

        $this->selected = $element;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->supportedValues);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->supportedValues);
    }

    /**
     * Return the selected value
     *
     * @return mixed
     */
    public function getSelected()
    {
        return $this->selected;
    }

    /**
     * @param mixed $value
     * @throws Exception\InvalidArgumentException
     */
    private function assertValueIsValid($value)
    {
        if (is_array($value) || is_object($value) || is_bool($value)) {
            throw new UnsupportedValueException('One of the value is not supported.');
        }
    }

    /**
     * @param mixed $element
     * @throws Exception\UnsupportedValueException
     */
    public function assertSupportedElement($element)
    {
        if (is_bool($element) ||
            is_object($element) ||
            false === in_array($element, $this->supportedValues)
        ) {
            $strElement = print_r($element, true);
            throw new UnsupportedValueException("'{$strElement}' value is not registered.");
        }
    }
}
 