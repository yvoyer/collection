<?php
/**
 * This file is part of the collection.local project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Collection;

use Closure;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Star\Component\Collection\Exception\InvalidArgumentException;

/**
 * Class TypedCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Collection
 *
 * @since 1.0.0
 */
class TypedCollection implements Collection, Selectable
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var ArrayCollection
     */
    private $collection;

    /**
     * @param string $type
     * @param array $elements
     *
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($type, array $elements = array())
    {
        $this->type = $type;
        $this->guardAgainstInvalidGivenType();

        $this->collection = new ArrayCollection();
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * Adds an element at the end of the collection.
     *
     * @param mixed $element The element to add.
     *
     * @return boolean Always TRUE.
     */
    public function add($element)
    {
        $this->assertElementIsOfType($element);

        return $this->collection->add($element);
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
        $this->guardAgainstInvalidGivenType();
        return $this->collection->count();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return \Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        $this->guardAgainstInvalidGivenType();
        return $this->collection->getIterator();
    }

    /**
     * Clears the collection, removing all elements.
     *
     * @return void
     */
    public function clear()
    {
        $this->guardAgainstInvalidGivenType();
        $this->collection->clear();
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
        $this->guardAgainstInvalidGivenType();
        return $this->collection->contains($element);
    }

    /**
     * Checks whether the collection is empty (contains no elements).
     *
     * @return boolean TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty()
    {
        $this->guardAgainstInvalidGivenType();
        return $this->collection->isEmpty();
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
        $this->guardAgainstInvalidGivenType();
        return $this->collection->remove($key);
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
        $this->guardAgainstInvalidGivenType();
        return $this->collection->removeElement($element);
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
        $this->guardAgainstInvalidGivenType();
        return $this->collection->containsKey($key);
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
        $this->guardAgainstInvalidGivenType();
        return $this->collection->get($key);
    }

    /**
     * Gets all keys/indices of the collection.
     *
     * @return array The keys/indices of the collection, in the order of the corresponding
     *               elements in the collection.
     */
    public function getKeys()
    {
        $this->guardAgainstInvalidGivenType();
        return $this->collection->getKeys();
    }

    /**
     * Gets all values of the collection.
     *
     * @return array The values of all elements in the collection, in the order they
     *               appear in the collection.
     */
    public function getValues()
    {
        $this->guardAgainstInvalidGivenType();
        return $this->collection->getValues();
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
        $this->assertElementIsOfType($value);

        $this->collection->set($key, $value);
    }

    /**
     * Gets a native PHP array representation of the collection.
     *
     * @return array
     */
    public function toArray()
    {
        $this->guardAgainstInvalidGivenType();
        return $this->collection->toArray();
    }

    /**
     * Sets the internal iterator to the first element in the collection and returns this element.
     *
     * @return mixed
     */
    public function first()
    {
        $this->guardAgainstInvalidGivenType();
        return $this->collection->first();
    }

    /**
     * Sets the internal iterator to the last element in the collection and returns this element.
     *
     * @return mixed
     */
    public function last()
    {
        $this->guardAgainstInvalidGivenType();
        return $this->collection->last();
    }

    /**
     * Gets the key/index of the element at the current iterator position.
     *
     * @return int|string
     */
    public function key()
    {
        $this->guardAgainstInvalidGivenType();
        return $this->collection->key();
    }

    /**
     * Gets the element of the collection at the current iterator position.
     *
     * @return mixed
     */
    public function current()
    {
        $this->guardAgainstInvalidGivenType();
        return $this->collection->current();
    }

    /**
     * Moves the internal iterator position to the next element and returns this element.
     *
     * @return mixed
     */
    public function next()
    {
        $this->guardAgainstInvalidGivenType();
        return $this->collection->next();
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
        $this->guardAgainstInvalidGivenType();
        return $this->collection->exists($p);
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
        $this->guardAgainstInvalidGivenType();
        $elements = $this->collection->filter($p)->toArray();

        return $this->create($elements);
    }

    /**
     * @param array $elements
     *
     * @return self
     */
    protected function create(array $elements = array())
    {
        return new static($this->type, $elements);
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
        $this->guardAgainstInvalidGivenType();
        return $this->collection->forAll($p);
    }

    /**
     * Applies the given public function to each element in the collection and returns
     * a new collection with the elements returned by the function.
     *
     * @param Closure $func
     *
     * @return Collection
     */
    public function map(Closure $func)
    {
        $this->guardAgainstInvalidGivenType();
        $elements = $this->collection->map($func)->toArray();
        $newCollection = $this->create();
        foreach ($elements as $key => $element) {
            try {
                $newCollection->set($key, $element);
            } catch (InvalidArgumentException $e) {
                // do nothing, because the mapped item is not instance of type
            }
        }

        return $newCollection;
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
        $this->guardAgainstInvalidGivenType();
        $partition = $this->collection->partition($p);

        return array(
            $this->create($partition[0]->toArray()),
            $this->create($partition[1]->toArray()),
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
        $this->guardAgainstInvalidGivenType();
        return $this->collection->indexOf($element);
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
        $this->guardAgainstInvalidGivenType();
        return $this->collection->slice($offset, $length);
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
        $this->guardAgainstInvalidGivenType();
        return isset($this->collection[$offset]);
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
        $this->guardAgainstInvalidGivenType();
        return $this->collection[$offset];
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
        $this->assertElementIsOfType($value);
        $this->collection[$offset] = $value;
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
        $this->guardAgainstInvalidGivenType();
        unset($this->collection[$offset]);
    }

    /**
     * @param $element
     * @throws Exception\InvalidArgumentException
     */
    private function assertElementIsOfType($element)
    {
        $this->guardAgainstInvalidGivenType();

        if (false === $element instanceof $this->type) {
            throw new InvalidArgumentException("The collection only supports adding {$this->type}.");
        }
    }

    /**
     * Selects all elements from a selectable that match the expression and
     * returns a new collection containing these elements.
     *
     * @param Criteria $criteria
     *
     * @return Collection
     */
    public function matching(Criteria $criteria)
    {
        $this->guardAgainstInvalidGivenType();
        $elements = $this->collection->matching($criteria)->toArray();

        return $this->create($elements);
    }

    private function guardAgainstInvalidGivenType()
    {
        if (empty($this->type)) {
            throw new InvalidArgumentException('The supported type should be given on construct.');
        }

        if (false === class_exists($this->type)) {
            if (false === interface_exists($this->type)) {
                throw new InvalidArgumentException("The class/interface '{$this->type}' must exists.");
            }
        }
    }
}
