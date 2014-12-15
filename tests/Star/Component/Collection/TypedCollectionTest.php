<?php
/**
 * This file is part of the collection.local project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection;

use Doctrine\Common\Collections\Criteria;
use stdClass;
use Star\Component\Collection\TypedCollection;
use tests\Star\Component\StarCollectionTestCase;

/**
 * Class TypedCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection
 *
 * @since 1.0.0
 */
class TypedCollectionTest extends StarCollectionTestCase
{
    /**
     * @var TypedCollection
     */
    private $collection;

    /**
     * @var \stdClass
     */
    private $element;

    /**
     * @var string
     */
    const COLLECTION_TYPE = 'Star\Component\Collection\TypedCollection';

    public function setUp()
    {
        $this->element = new \stdClass();
        $this->collection = new TypedCollection('\stdClass');
    }

    public function testShouldBeACollection()
    {
        $this->assertInstanceOfCollection($this->collection);
    }

    public function testShouldBeAbleToBuildCollectionInternallyWithoutTheType()
    {
        $this->assertInstanceOf(self::COLLECTION_TYPE, $this->collection->matching(new Criteria()));
    }

    /**
     * @expectedException        \Star\Component\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The collection only supports adding \stdClass.
     */
    public function testShouldThrowExceptionWhenTryingToAddNonSupportedType()
    {
        $this->collection->add(1);
    }

    /**
     * @expectedException        \Star\Component\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The collection only supports adding \stdClass.
     */
    public function testShouldThrowExceptionWhenTryingToAddNonSupportedTypeAsAnArray()
    {
        $this->collection[] = 1;
    }

    /**
     * @expectedException        \Star\Component\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The collection only supports adding \stdClass.
     */
    public function testShouldThrowExceptionWhenTryingToSetNonSupportedType()
    {
        $this->collection->set(0, 1);
    }

    /**
     * @expectedException        \Star\Component\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The collection only supports adding \stdClass.
     */
    public function testShouldThrowExceptionWhenTryingCreateCollectionWithNonSupportedType()
    {
        new TypedCollection('\stdClass', array(3));
    }

    public function testShouldAddTheSupportedObject()
    {
        $this->assertCount(0, $this->collection);
        $this->assertTrue($this->collection->add(new \stdClass()));
        $this->assertCount(1, $this->collection);
        $this->assertTrue($this->collection->add(new \stdClass()));
        $this->assertCount(2, $this->collection);
        $this->assertTrue($this->collection->add(new \stdClass()));
        $this->assertCount(3, $this->collection);
    }

    public function testShouldBeIteratableByForeach()
    {
        $this->element = $this->getMock('\stdClass', array('method'));
        $this->element
            ->expects($this->once())
            ->method('method');
        $this->collection->add($this->element);

        foreach ($this->collection as $key => $object) {
            $this->assertSame(0, $key);
            $this->assertSame($this->element, $object);
            $object->method();
        }
    }

    public function testShouldClearTheCollection()
    {
        $this->collection->add($this->element);

        $this->assertCount(1, $this->collection);
        $this->collection->clear();
        $this->assertCount(0, $this->collection);
    }

    public function testShouldReturnWhetherTheElementIsInTheCollection()
    {
        $this->assertFalse($this->collection->contains($this->element));
        $this->collection->add($this->element);
        $this->assertTrue($this->collection->contains($this->element));
    }

    /**
     * @depends testShouldClearTheCollection
     */
    public function testShouldReturnWhetherTheCollectionIsEmpty()
    {
        $this->assertTrue($this->collection->isEmpty());
        $this->collection->add($this->element);
        $this->assertFalse($this->collection->isEmpty());
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldRemoveTheElementByIndex()
    {
        $this->collection->add($this->element);
        $this->assertCount(1, $this->collection);
        $this->collection->remove(0);
        $this->assertCount(0, $this->collection);
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldRemoveTheElement()
    {
        $this->collection->add($this->element);
        $this->assertCount(1, $this->collection);
        $this->assertTrue($this->collection->removeElement($this->element));
        $this->assertCount(0, $this->collection);
        $this->assertFalse($this->collection->removeElement($this->element));
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldReturnWhetherTheCollectionContainsTheElement()
    {
        $this->assertFalse($this->collection->containsKey(0));
        $this->collection->add($this->element);
        $this->assertTrue($this->collection->containsKey(0));
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldReturnTheElementAtKey()
    {
        $this->assertNull($this->collection->get(0));
        $this->collection->add($this->element);
        $this->assertSame($this->element, $this->collection->get(0));
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldReturnTheKeys()
    {
        $this->assertCount(0, $this->collection->getKeys());
        $this->collection->add($this->element);
        $this->assertSame(array(0), $this->collection->getKeys());
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldReturnTheValues()
    {
        $this->assertCount(0, $this->collection->getValues());
        $this->collection->add($this->element);
        $this->assertSame(array($this->element), $this->collection->getValues());
    }

    public function testShouldSetTheElementAtIndex()
    {
        $this->assertCount(0, $this->collection);
        $this->collection->set('index', $this->element);
        $this->assertCount(1, $this->collection);
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldReturnTheArrayRepresentation()
    {
        $this->assertSame(array(), $this->collection->toArray());
        $this->collection->add($this->element);
        $this->assertSame(array(0 => $this->element), $this->collection->toArray());
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldReturnTheElementsBasedOnPosition()
    {
        $this->assertNull($this->collection->first());
        $this->assertNull($this->collection->last());

        $this->collection->add($firstElement = new \stdClass());
        $this->collection->add($secondElement = new \stdClass());

        $this->assertSame($firstElement, $this->collection->first());
        $this->assertSame($secondElement, $this->collection->last());
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldReturnTheKey()
    {
        $this->assertNull($this->collection->key());
        $this->collection->add($this->element);
        $this->assertSame(0, $this->collection->key());
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldReturnTheCurrentElement()
    {
        $this->assertFalse($this->collection->current());
        $this->collection->add($this->element);
        $this->assertSame($this->element, $this->collection->current());
    }

    /**
     * @depends testShouldReturnTheCurrentElement
     */
    public function testShouldChangeThePointerToNextElement()
    {
        $this->collection->add($firstElement = new \stdClass());
        $this->collection->add($secondElement = new \stdClass());
        $this->collection->first();

        $this->assertSame($firstElement, $this->collection->current());
        $this->assertSame($secondElement, $this->collection->next());
    }

    /**
     * @depends testShouldSetTheElementAtIndex
     */
    public function testShouldReturnTheElementAtIndex()
    {
        $this->collection->set('index', $this->element);
        $this->assertSame('index', $this->collection->indexOf($this->element));
    }

    public function testShouldBeArrayAccess()
    {
        // isset false
        $this->assertFalse(isset($this->collection[0]));
        $this->assertFalse(isset($this->collection['index']));

        // offsetSet
        $this->collection['index'] = $firstElement = new \stdClass();
        $this->collection[] = $secondElement = new \stdClass();

        // isset true
        $this->assertTrue(isset($this->collection[0]));
        $this->assertTrue(isset($this->collection['index']));

        // offsetGet
        $this->assertSame($firstElement, $this->collection['index']);
        $this->assertSame($secondElement, $this->collection[0]);

        unset($this->collection['index'], $this->collection[0]);

        // isset false
        $this->assertFalse(isset($this->collection[0]));
        $this->assertFalse(isset($this->collection['index']));
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldSliceTheCollection()
    {
        $this->collection->add($first = new \stdClass());
        $this->collection->add($second = new \stdClass());
        $this->collection->add($third = new \stdClass());
        $this->collection->add($fourth = new \stdClass());
        $this->collection->add($fifth = new \stdClass());

        $expected = array(
            1 => $second,
            2 => $third,
            3 => $fourth,
        );
        $this->assertSame($expected, $this->collection->slice(1, 3));
        $this->assertCount(5, $this->collection);
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldFilterTheCollectionBasedOnClosure()
    {
        $this->collection->add($first = (object) array('id' => 1));
        $this->collection->add($second = (object) array('id' => 2));
        $this->collection->add($third = (object) array('id' => 2));
        $this->collection->add($fourth = (object) array('id' => 2));
        $this->collection->add($fifth = (object) array('id' => 3));

        $closure = function ($element) {
            return ($element->id === 2) ? $element : null;
        };

        $expected = array(
            0 => $second,
            1 => $third,
            2 => $fourth,
        );

        $filteredCollection = $this->collection->filter($closure);
        $this->assertInstanceOf(self::COLLECTION_TYPE, $filteredCollection);
        $this->assertSame($expected, $filteredCollection->toArray());
        $this->assertCount(5, $this->collection);
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldReturnWhetherTheElementExists()
    {
        $this->collection->add($element = new \stdClass());

        $this->assertTrue($this->collection->exists(function () { return true; }));
        $this->assertFalse($this->collection->exists(function () {}));
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldReturnWhetherTheClosureApplyToAllElements()
    {
        $this->collection->add($element = new \stdClass());

        $this->assertTrue($this->collection->forAll(function () { return true; }));
        $this->assertFalse($this->collection->forAll(function () {}));
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldMapReturnTheMapOfElementReturnedByClosure()
    {
        $this->collection->add($first = new \stdClass());
        $this->collection->add($second = (object) array('id' => 1));
        $this->collection->add($third = new \stdClass());

        $expected = array(1 => $second);
        $closure = function ($element) {
            if (isset($element->id)) return $element;
        };
        $newCollection = $this->collection->map($closure);
        $this->assertInstanceOf(self::COLLECTION_TYPE, $newCollection);
        $this->assertSame($expected, $newCollection->toArray());
        $this->assertCount(3, $this->collection);
        $this->assertContainsOnlyInstancesOf('stdClass', $this->collection);
    }

    /**
     * @depends testShouldAddTheSupportedObject
     */
    public function testShouldPartitionTheCollectionInTwo()
    {
        $this->collection->add($first = new \stdClass());
        $this->collection->add($second = (object) array('id' => 1));
        $this->collection->add($third = new \stdClass());

        $closure = function ($key, $element) {
            if (isset($element->id)) return $element;
        };
        $actual = $this->collection->partition($closure);
        $this->assertCount(2, $actual);
        $this->assertContainsOnlyInstancesOf(self::COLLECTION_TYPE, $actual);
        $this->assertSame(array($second), $actual[0]->toArray());
        $this->assertSame(array($first, $third), $actual[1]->toArray());
    }

    /**
     * @ticket #17
     */
    public function testShouldMatchTheElementFromExtendedCollection()
    {
        $collection = new ExtendedStubCollection();
        $this->assertInstanceOf(__NAMESPACE__ . '\ExtendedStubCollection', $collection->matching(new Criteria()));
    }

    /**
     * @expectedException        \Star\Component\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The class/interface 'qwewq' must exists.
     */
    public function testShouldThrowExceptionWhenClassDoNotExists()
    {
        new TypedCollection('qwewq');
    }

    public function testShouldSupportInterface()
    {
        $this->collection = new TypedCollection('\Countable');
        $this->collection->add($this->getMock('\Countable'));
        $this->assertCount(1, $this->collection);
    }

    /**
     * @ticket #17
     */
    public function testShouldFilterExtendedCollection()
    {
        $object = new \stdClass();
        $object->id = 22;

        $collection = new ExtendedStubCollection(array($object));
        $result = $collection->findByObject($object);
        $this->assertSame($object, $result);
    }

    /**
     * @ticket #17
     */
    public function testShouldPartitionExtendedCollection()
    {
        $this->collection = new ExtendedStubCollection(array(
            $first = new \stdClass(),
            $second = (object) array('id' => 1),
            $third = new \stdClass(),
        ));

        $closure = function ($key, $element) {
            if (isset($element->id)) return $element;
        };
        $actual = $this->collection->partition($closure);
        $this->assertCount(2, $actual);
        $this->assertContainsOnlyInstancesOf(get_class($this->collection), $actual);
        $this->assertSame(array($second), $actual[0]->toArray());
        $this->assertSame(array($first, $third), $actual[1]->toArray());
    }

    /**
     * @depends testShouldAddTheSupportedObject
     * @ticket #17
     */
    public function testShouldReturnTheMapOfElementsReturnedByClosureOnAnExtendedCollection()
    {
        $this->collection = new ExtendedStubCollection(array(
            $first = new \stdClass(),
            $second = (object) array('id' => 1),
            $third = new \stdClass(),
        ));

        $expected = array(1 => $second);
        $closure = function ($element) {
            if (isset($element->id)) return $element;
        };
        $newCollection = $this->collection->map($closure);
        $this->assertInstanceOf(__NAMESPACE__ . '\ExtendedStubCollection', $newCollection);
        $this->assertSame($expected, $newCollection->toArray());
        $this->assertCount(3, $this->collection);
        $this->assertContainsOnlyInstancesOf('stdClass', $this->collection);
    }

    /**
     * @ticket #17
     */
    public function testAddShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->add(new \stdClass());
    }

    /**
     * @ticket #17
     */
    public function testCountShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->count();
    }

    /**
     * @ticket #17
     */
    public function testGetIteratorShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->getIterator();
    }

    /**
     * @ticket #17
     */
    public function testClearShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->clear();
    }

    /**
     * @ticket #17
     */
    public function testContainsShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->contains(new stdClass());
    }

    /**
     * @ticket #17
     */
    public function testIsEmptyShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->isEmpty();
    }

    /**
     * @ticket #17
     */
    public function testRemoveShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->remove('');
    }

    /**
     * @ticket #17
     */
    public function testRemoveElementShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->removeElement('');
    }

    /**
     * @ticket #17
     */
    public function testContainsKeyShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->containsKey('');
    }

    /**
     * @ticket #17
     */
    public function testGetShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->get('');
    }

    /**
     * @ticket #17
     */
    public function testGetKeysShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->getKeys();
    }

    /**
     * @ticket #17
     */
    public function testGetValuesShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->getValues();
    }

    /**
     * @ticket #17
     */
    public function testSetShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->set('key', 'value');
    }

    /**
     * @ticket #17
     */
    public function testToArrayShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->toArray();
    }

    /**
     * @ticket #17
     */
    public function testFirstShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->first();
    }

    /**
     * @ticket #17
     */
    public function testLastShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->last();
    }

    /**
     * @ticket #17
     */
    public function testKeyShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->key();
    }

    /**
     * @ticket #17
     */
    public function testCurrentShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->current();
    }

    /**
     * @ticket #17
     */
    public function testNextShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->next();
    }

    /**
     * @ticket #17
     */
    public function testExistsShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->exists(function () {});
    }

    /**
     * @ticket #17
     */
    public function testFilterShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->filter(function () {});
    }

    /**
     * @ticket #17
     */
    public function testForAllShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->forAll(function () {});
    }

    /**
     * @ticket #17
     */
    public function testMapShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->map(function () {});
    }

    /**
     * @ticket #17
     */
    public function testPartitionShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->partition(function () {});
    }

    /**
     * @ticket #17
     */
    public function testIndexOfShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->indexOf('');
    }

    /**
     * @ticket #17
     */
    public function testSliceShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->slice('', 8);
    }

    /**
     * @ticket #17
     */
    public function testOffsetExistsShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->offsetExists(8);
    }

    /**
     * @ticket #17
     */
    public function testOffsetGetShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->offsetGet(8);
    }

    /**
     * @ticket #17
     */
    public function testOffsetSetShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->offsetSet(8, '');
    }

    /**
     * @ticket #17
     */
    public function testOffsetUnsetShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->offsetUnset(8);
    }

    /**
     * @ticket #17
     */
    public function testMatchingShouldThrowExceptionWhenTypeNotDefinedByExtendedCollection()
    {
        $this->assertExceptionForInvalidSupportedTypeGivenIsThrown();
        $collection = new BadExtendedCollectionStub();
        $collection->matching(new Criteria());
    }

    private function assertExceptionForInvalidSupportedTypeGivenIsThrown()
    {
        $this->setExpectedException(
            'Star\Component\Collection\Exception\InvalidArgumentException',
            'The supported type should be given on construct.'
        );
    }
}

class ExtendedStubCollection extends TypedCollection
{
    public function __construct(array $elements = array())
    {
        parent::__construct('\stdClass', $elements);
    }

    public function findByObject(\stdClass $object)
    {
        $closure = function($class) use ($object) {
            return $class->id == $object->id;
        };

        return $this->filter($closure)->first();
    }

    protected function create(array $elements = array())
    {
        return new self($elements);
    }
}

class BadExtendedCollectionStub extends TypedCollection
{
    public function __construct()
    {
        // Should define a type
    }
}
