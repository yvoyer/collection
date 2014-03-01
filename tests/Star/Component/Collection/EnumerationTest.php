<?php
/**
 * This file is part of the collection.local project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection;

use Star\Component\Collection\Enumeration;
use tests\Star\Component\StarCollectionTestCase;

/**
 * Class EnumerationTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection
 */
class EnumerationTest extends StarCollectionTestCase
{
    /**
     * @var Enumeration
     */
    private $collection;

    private $values = array(
        3,
        '5',
        213.321,
        'valid'
    );

    public function setUp()
    {
        $this->collection = new Enumeration($this->values);
    }

    public function testShouldBeACollection()
    {
        $this->assertInstanceOfStarCollection($this->collection);
    }

    /**
     * @dataProvider provideSupportedTypes
     *
     * @param $value
     */
    public function testShouldSelectOnlyRegisteredValues($value)
    {
        $this->assertNull($this->collection->getSelected());
        $this->collection->select($value);
        $this->assertSame($value, $this->collection->getSelected());
    }

    public function provideSupportedTypes()
    {
        return array(
            'Should accept registered integer' => array(3),
            'Should accept registered integer as string' => array('3'),
            'Should accept registered string' => array('valid'),
            'Should accept registered float' => array(213.321),
            'Should accept registered float as string' => array('213.321'),
        );
    }

    /**
     * @depends testShouldSelectOnlyRegisteredValues
     */
    public function testShouldChangeTheSelectedValue()
    {
        $this->collection->select(3);
        $this->assertSame(3, $this->collection->getSelected());
        $this->collection->select('valid');
        $this->assertSame('valid', $this->collection->getSelected());
    }

    /**
     * @dataProvider provideUnSupportedTypes
     *
     * @param $value
     *
     * @expectedException        \Star\Component\Collection\Exception\UnsupportedValueException
     * @expectedExceptionMessage One of the value is not supported.
     */
    public function testShouldThrowExceptionWhenInitialisingWithInvalidValue($value)
    {
        new Enumeration(array($value));
    }

    public function provideUnSupportedTypes()
    {
        return array(
            'Should refuse array' => array(array()),
            'Should refuse object' => array(new \stdClass()),
            'Should refuse false' => array(false),
            'Should refuse true' => array(true), // Having true, mess up the search for valid element
        );
    }

    /**
     * @expectedException        \Star\Component\Collection\Exception\InvalidArgumentException
     * @expectedExceptionMessage Values should be given.
     */
    public function testShouldThrowExceptionWhenEmptyArrayGiven()
    {
        new Enumeration(array());
    }

    /**
     * @dataProvider provideUnregisteredTypes
     *
     * @param $value
     *
     * @expectedException        \Star\Component\Collection\Exception\UnsupportedValueException
     * @expectedExceptionMessage ' value is not registered.
     */
    public function testShouldThrowExceptionWhenSelectingUnregisteredValue($value)
    {
        $this->collection->select($value);
    }

    public function provideUnregisteredTypes()
    {
        return array(
            'Should refuse unregistered integer' => array(2),
            'Should refuse unregistered integer as string' => array('4'),
            'Should refuse unregistered string' => array('invalid'),
            'Should refuse unregistered float' => array(999.999),
            'Should refuse unregistered float as string' => array('888.888'),
            'Should refuse unregistered false' => array(false),
            'Should refuse unregistered true' => array(true),
            'Should refuse unregistered array' => array(array()),
            'Should refuse unregistered object' => array(new \stdClass()),
        );
    }

    public function testShouldBeIteratable()
    {
        $i = 0;
        foreach ($this->collection as $element) {
            $this->assertSame($this->values[$i], $element);

            $i ++;
        }
    }

    public function testShouldReturnTheNumberOfElements()
    {
        $this->assertCount(4, $this->collection);
    }
}
 