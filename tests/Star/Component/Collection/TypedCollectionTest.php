<?php
/**
 * This file is part of the collection.local project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection;

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

    public function setUp()
    {
        $this->element = new \stdClass();
        $this->collection = new TypedCollection('\stdClass');
    }

    public function testShouldBeACollection()
    {
        $this->assertInstanceOfCollection($this->collection);
    }

    /**
     * @expectedException        \Star\Component\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The collection only supports adding \stdClass.
     */
    public function testShouldThrowExceptionWhenTryingToAddNonSupportedType()
    {
        $this->collection->add(1);
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
}
 