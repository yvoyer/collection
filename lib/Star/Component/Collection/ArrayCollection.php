<?php
/**
 * This file is part of the collection.local project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Collection;

use Traversable;

/**
 * Class ArrayCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Collection
 */
class ArrayCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var array
     */
    private $data = array();

    /**
     * @param mixed $element
     */
    public function add($element)
    {
        $this->data[] = $element;
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
        return count($this->data);
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
        return new \ArrayIterator($this->data);
    }
}
 