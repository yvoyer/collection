<?php
/**
 * This file is part of the collection.local project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Collection;

use Star\Component\Collection\Exception\InvalidArgumentException;
use Traversable;

/**
 * Class TypedCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Collection
 */
class TypedCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var ArrayCollection
     */
    private $data;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
        $this->data = new ArrayCollection();
    }

    /**
     * @param mixed $element
     * @throws Exception\InvalidArgumentException
     */
    public function add($element)
    {
        if (false === $element instanceof $this->type) {
            throw new InvalidArgumentException("The collection only supports adding {$this->type}.");
        }

        $this->data->add($element);
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
        return $this->data->count();
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
        return $this->data->getIterator();
    }
}
 