<?php
/**
 * This file is part of the collection.local project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests;

use Star\Component\Collection\ArrayCollection;

/**
 * Class ArrayCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests
 */
class ArrayCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayCollection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new ArrayCollection();
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
 