<?php
/**
 * This file is part of the collection.local project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection;

use Star\Component\Collection\ArrayCollection;
use tests\Star\Component\StarCollectionTestCase;

/**
 * Class ArrayCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection
 */
class ArrayCollectionTest extends StarCollectionTestCase
{
    /**
     * @var ArrayCollection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new ArrayCollection();
    }

    public function testShouldBeACollection()
    {
        $this->assertInstanceOfStarCollection($this->collection);
    }

    public function testShouldAddElements()
    {
        $this->assertCount(0, $this->collection);
        $this->collection->add(1);
        $this->assertCount(1, $this->collection);
        $this->collection->add(1);
        $this->assertCount(2, $this->collection);
    }

    public function testShouldBeIteratableOn()
    {
        $this->assertInstanceOf('\IteratorAggregate', $this->collection);

        $this->collection->add(1);
        foreach ($this->collection as $element) {
            $this->assertSame(1, $element);
        }
    }
}
 