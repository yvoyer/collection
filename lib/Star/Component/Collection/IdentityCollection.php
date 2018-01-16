<?php
/**
 * This file is part of the StarCollection project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Collection;

use Closure;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Star\Component\Collection\Exception\DuplicatedIdentityException;
use Star\Component\Collection\UniqueId\UniqueId;
use Traversable;

/**
 * Class IdentityCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Collection
 */
class IdentityCollection implements Collection
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var TypedCollection
     */
    private $elements;

    /**
     * @var string
     */
    private $type;

    /**
     * Whether to throw exception when duplicate identity is found.
     *
     * @var bool
     */
    private $throwExceptionOnDuplicate = false;

    /**
     * @param string $className
     */
    public function __construct($className, array $elements = array())
    {
        $this->type = $className;
        $this->elements = new TypedCollection($className, $elements);
    }

    /**
     * Adds an element at the end of the collection.
     *
     * @param UniqueIdentity|mixed $element The element to add.
     *
     * @return boolean Always TRUE.
     */
    public function add($element)
    {
        $this->guardAgainstDuplicatedElement($element);

        $isAdded = false;
        if (false === $this->contains($element)) {
            $isAdded = true;
            $this->offsetSet($element->uId()->id(), $element);
        }

        return $isAdded;
    }

    /**
     * Clears the collection, removing all elements.
     *
     * @return void
     */
    public function clear()
    {
        $this->elements->clear();
    }

    /**
     * Checks whether an element is contained in the collection.
     * This is an O(n) operation, where n is the size of the collection.
     *
     * @param mixed $element The element to search for.
     *
     * @return boolean TRUE if the collection contains the element, FALSE otherwise.
     */
    public function contains($element)
    {
        /**
         * @var $element UniqueIdentity
         */
        return $this->offsetExists($element->uId()->id());
    }

    /**
     * Checks whether the collection is empty (contains no elements).
     *
     * @return boolean TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty()
    {
        return $this->elements->isEmpty();
    }

    /**
     * Removes the element at the specified index from the collection.
     *
     * @param string|integer $key The kex/index of the element to remove.
     *
     * @return mixed The removed element or NULL, if the collection did not contain the element.
     */
    public function remove($key)
    {
        if ($key instanceof UniqueId) {
            $key = $key->id();
        }

        return $this->elements->remove($key);
    }

    /**
     * Removes the specified element from the collection, if it is found.
     *
     * @param mixed $element The element to remove.
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeElement($element)
    {
        /**
         * @var UniqueIdentity $element
         */
        return (bool) $this->remove($element->uId());
    }

    /**
     * Checks whether the collection contains an element with the specified key/index.
     *
     * @param string|integer $key The key/index to check for.
     *
     * @return boolean TRUE if the collection contains an element with the specified key/index,
     *                 FALSE otherwise.
     */
    public function containsKey($key)
    {
        if ($key instanceof UniqueId) {
            $key = $key->id();
        }

        return $this->elements->containsKey($key);
    }

    /**
     * Gets the element at the specified key/index.
     *
     * @param string|integer $key The key/index of the element to retrieve.
     *
     * @return mixed
     */
    public function get($key)
    {
        if ($key instanceof UniqueId) {
            $key = $key->id();
        }

        return $this->elements->get($key);
    }

    /**
     * Gets all keys/indices of the collection.
     *
     * @return array The keys/indices of the collection, in the order of the corresponding
     *               elements in the collection.
     */
    public function getKeys()
    {
        return $this->elements->getKeys();
    }

    /**
     * Gets all values of the collection.
     *
     * @return array The values of all elements in the collection, in the order they
     *               appear in the collection.
     */
    public function getValues()
    {
        return $this->elements->getValues();
    }

    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param string|integer $key The key/index of the element to set.
     * @param mixed $value The element to set.
     *
     * @return void
     */
    public function set($key, $value)
    {
        /**
         * @var UniqueIdentity $value
         */
        $this->elements->set($value->uId()->id(), $value);
    }

    /**
     * Gets a native PHP array representation of the collection.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->elements->toArray();
    }

    /**
     * Sets the internal iterator to the first element in the collection and returns this element.
     *
     * @return mixed
     */
    public function first()
    {
        return $this->elements->first();
    }

    /**
     * Sets the internal iterator to the last element in the collection and returns this element.
     *
     * @return mixed
     */
    public function last()
    {
        return $this->elements->last();
    }

    /**
     * Gets the key/index of the element at the current iterator position.
     *
     * @return int|string
     */
    public function key()
    {
        return $this->elements->key();
    }

    /**
     * Gets the element of the collection at the current iterator position.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->elements->current();
    }

    /**
     * Moves the internal iterator position to the next element and returns this element.
     *
     * @return mixed
     */
    public function next()
    {
        return $this->elements->next();
    }

    /**
     * Tests for the existence of an element that satisfies the given predicate.
     *
     * @param Closure $p The predicate.
     *
     * @return boolean TRUE if the predicate is TRUE for at least one element, FALSE otherwise.
     */
    public function exists(Closure $p)
    {
        return $this->elements->exists($p);
    }

    /**
     * Returns all the elements of this collection that satisfy the predicate p.
     * The order of the elements is preserved.
     *
     * @param Closure $p The predicate used for filtering.
     *
     * @return Collection A collection with the results of the filter operation.
     */
    public function filter(Closure $p)
    {
        return $this->elements->filter($p);
    }

    /**
     * Tests whether the given predicate p holds for all elements of this collection.
     *
     * @param Closure $p The predicate.
     *
     * @return boolean TRUE, if the predicate yields TRUE for all elements, FALSE otherwise.
     */
    public function forAll(Closure $p)
    {
        return $this->elements->forAll($p);
    }

    /**
     * Applies the given public function to each element in the collection and returns
     * a new collection with the elements returned by the public function.
     *
     * @param Closure $func
     *
     * @return Collection The returned collection is not typed anymore, since the values may not be of the class type.
     */
    public function map(Closure $func)
    {
        return new ArrayCollection($this->elements->map($func)->getValues());
    }

    /**
     * Partitions this collection in two collections according to a predicate.
     * Keys are preserved in the resulting collections.
     *
     * @param Closure $p The predicate on which to partition.
     *
     * @return array An array with two elements. The first element contains the collection
     *               of elements where the predicate returned TRUE, the second element
     *               contains the collection of elements where the predicate returned FALSE.
     */
    public function partition(Closure $p)
    {
        $partition = $this->elements->partition($p);

        return array(
            new static($this->type, $partition[0]->toArray()),
            new static($this->type, $partition[1]->toArray()),
        );
    }

    /**
     * Gets the index/key of a given element. The comparison of two elements is strict,
     * that means not only the value but also the type must match.
     * For objects this means reference equality.
     *
     * @param mixed $element The element to search for.
     *
     * @return int|string|bool The key/index of the element or FALSE if the element was not found.
     */
    public function indexOf($element)
    {
        return $this->elements->indexOf($element);
    }

    /**
     * Extracts a slice of $length elements starting at position $offset from the Collection.
     *
     * If $length is null it returns all elements from $offset to the end of the Collection.
     * Keys have to be preserved by this method. Calling this method will only return the
     * selected slice and NOT change the elements contained in the collection slice is called on.
     *
     * @param int $offset The offset to start from.
     * @param int|null $length The maximum number of elements to return, or null for no limit.
     *
     * @return array
     */
    public function slice($offset, $length = null)
    {
        return $this->elements->slice($offset, $length);
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
        return $this->elements->getIterator();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        if ($offset instanceof UniqueId) {
            $offset = $offset->id();
        }

        return $this->elements->offsetExists($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if ($offset instanceof UniqueId) {
            $offset = $offset->id();
        }

        return $this->elements->offsetGet($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        /**
         * @var UniqueIdentity $value
         */
        $this->elements->offsetSet($value->uId()->id(), $value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        if ($offset instanceof UniqueId) {
            $offset = $offset->id();
        }

        $this->elements->offsetUnset($offset);
    }

    /**
     * Count elements of an object
     *
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return $this->elements->count();
    }

    /**
     * Set the flag to throw exception on duplicated element.
     */
    public function throwExceptionOnDuplicate()
    {
        $this->throwExceptionOnDuplicate = true;
    }

    /**
     * @param UniqueIdentity $element
     * @throws Exception\DuplicatedIdentityException
     */
    private function guardAgainstDuplicatedElement(UniqueIdentity $element)
    {
        if ($this->throwExceptionOnDuplicate && $this->contains($element)) {
            throw new DuplicatedIdentityException("The element '{$element->uId()->id()}' is already present in the collection.");
        }
    }
}
