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
 */
class TypedCollectionTest extends StarCollectionTestCase
{
    /**
     * @var TypedCollection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new TypedCollection('\stdClass');
    }

    public function testShouldBeACollection()
    {
        $this->assertInstanceOfStarCollection($this->collection);
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
        $this->collection->add(new \stdClass());
        $this->assertCount(1, $this->collection);
        $this->collection->add(new \stdClass());
        $this->assertCount(2, $this->collection);
        $this->collection->add(new \stdClass());
        $this->assertCount(3, $this->collection);
    }

    public function testShouldBeIteratableByForeach()
    {
        $stdClass = $this->getMock('\stdClass', array('method'));
        $stdClass
            ->expects($this->once())
            ->method('method');

        $this->collection->add($stdClass);

        foreach ($this->collection as $object) {
            $object->method();
        }
    }
}
 