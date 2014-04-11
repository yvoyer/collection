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
     * @throws \RuntimeException
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($type = null, array $elements = array())
    {
        $this->assertTypeIsValid($type);

        $this->addElements($elements);
    }

    /**
     * @param array $elements
     */
    public function addElements(array $elements)
    {
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

        return $this->getCollection()->add($element);
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
        return $this->getCollection()->count();
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
        return $this->getCollection()->getIterator();
    }

    /**
     * Clears the collection, removing all elements.
     *
     * @return void
     */
    public function clear()
    {
        $this->getCollection()->clear();
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
        return $this->getCollection()->contains($element);
    }

    /**
     * Checks whether the collection is empty (contains no elements).
     *
     * @return boolean TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty()
    {
        return $this->getCollection()->isEmpty();
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
        return $this->getCollection()->remove($key);
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
        return $this->getCollection()->removeElement($element);
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
        return $this->getCollection()->containsKey($key);
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
        return $this->getCollection()->get($key);
    }

    /**
     * Gets all keys/indices of the collection.
     *
     * @return array The keys/indices of the collection, in the order of the corresponding
     *               elements in the collection.
     */
    public function getKeys()
    {
        return $this->getCollection()->getKeys();
    }

    /**
     * Gets all values of the collection.
     *
     * @return array The values of all elements in the collection, in the order they
     *               appear in the collection.
     */
    public function getValues()
    {
        return $this->getCollection()->getValues();
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

        $this->getCollection()->set($key, $value);
    }

    /**
     * Gets a native PHP array representation of the collection.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getCollection()->toArray();
    }

    /**
     * Sets the internal iterator to the first element in the collection and returns this element.
     *
     * @return mixed
     */
    public function first()
    {
        return $this->getCollection()->first();
    }

    /**
     * Sets the internal iterator to the last element in the collection and returns this element.
     *
     * @return mixed
     */
    public function last()
    {
        return $this->getCollection()->last();
    }

    /**
     * Gets the key/index of the element at the current iterator position.
     *
     * @return int|string
     */
    public function key()
    {
        return $this->getCollection()->key();
    }

    /**
     * Gets the element of the collection at the current iterator position.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->getCollection()->current();
    }

    /**
     * Moves the internal iterator position to the next element and returns this element.
     *
     * @return mixed
     */
    public function next()
    {
        return $this->getCollection()->next();
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
        return $this->getCollection()->exists($p);
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
        $elements = $this->getCollection()->filter($p)->toArray();

        return $this->create($elements);
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
        return $this->getCollection()->forAll($p);
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
        $elements = $this->getCollection()->map($func)->toArray();
        $newCollection = new TypedCollection($this->type);
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
        $partition = $this->getCollection()->partition($p);

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
        return $this->getCollection()->indexOf($element);
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
        return $this->getCollection()->slice($offset, $length);
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
        return isset($this->getCollection()[$offset]);
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
        return $this->getCollection()[$offset];
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
        $this->getCollection()[$offset] = $value;
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
        unset($this->getCollection()[$offset]);
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
        $elements = $this->getCollection()->matching($criteria)->toArray();

        return $this->create($elements);
    }

    /**
     * Returns the Type.
     *
     * @return string
     */
    protected function getType()
    {
        return $this->type;
    }

    /**
     * @param $element
     * @throws Exception\InvalidArgumentException
     */
    private function assertElementIsOfType($element)
    {
        if (false === $element instanceof $this->type) {
            throw new InvalidArgumentException("The collection only supports adding {$this->type}.");
        }
    }

    /**
     * Returns an instance of this class.
     *
     * @param array $elements
     *
     * @return static
     */
    protected function create(array $elements)
    {
        return new static($this->getType(), $elements);
    }

    /**
     * @param $type
     * @throws \RuntimeException
     * @throws Exception\InvalidArgumentException
     */
    private function assertTypeIsValid($type)
    {
        $this->type = $type;
        $this->type = $this->getType();

        if (null === $this->type) {
            throw new \RuntimeException('The type should be defined.');
        }

        if (false === class_exists($this->type)) {
            if (false === interface_exists($this->type)) {
                throw new InvalidArgumentException("The class/interface '{$this->type}' must exists.");
            }
        }
    }

    /**
     * @return ArrayCollection
     */
    private function getCollection()
    {
        if (null === $this->collection) {
            $this->collection = new ArrayCollection();
        }

        return $this->collection;
    }
}
