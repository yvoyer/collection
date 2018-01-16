<?php
/**
 * This file is part of the StarCollection project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component\Collection;

use PHPUnit\Framework\TestCase;
use Star\Component\Collection\IdentityCollection;
use Star\Component\Collection\UniqueId\ConfigurableId;
use Star\Component\Collection\UniqueIdentity;

/**
 * Class IdentityCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component\Collection
 */
final class IdentityCollectionTest extends TestCase
{
    /**
     * @var IdentityCollection
     */
    private $collection;

    /**
     * @var UniqueIdentity|\PHPUnit_Framework_MockObject_MockObject
     */
    private $identity1;

    /**
     * @var UniqueIdentity|\PHPUnit_Framework_MockObject_MockObject
     */
    private $identity2;

    /**
     * @var UniqueIdentity|\PHPUnit_Framework_MockObject_MockObject
     */
    private $identity3;

    public function setUp()
    {
        $this->identity1 = $this->createMock(UniqueIdentity::INTERFACE_NAME);
        $this->identity1
            ->expects($this->any())
            ->method('uId')
            ->will($this->returnValue(new ConfigurableId(1)));

        $this->identity2 = $this->createMock(UniqueIdentity::INTERFACE_NAME);
        $this->identity2
            ->expects($this->any())
            ->method('uId')
            ->will($this->returnValue(new ConfigurableId(2)));

        $this->identity3 = $this->createMock(UniqueIdentity::INTERFACE_NAME);
        $this->identity3
            ->expects($this->any())
            ->method('uId')
            ->will($this->returnValue(new ConfigurableId(3)));

        $this->collection = new IdentityCollection(UniqueIdentity::INTERFACE_NAME);
    }

    public function test_should_add_elements()
    {
        $this->assertCount(0, $this->collection);
        $this->assertTrue($this->collection->add($this->identity1));
        $this->assertFalse($this->collection->add($this->identity1));
        $this->assertCount(1, $this->collection);
    }

    /**
     * @expectedException        \Star\Component\Collection\Exception\DuplicatedIdentityException
     * @expectedExceptionMessage The element '1' is already present in the collection.
     */
    public function test_should_throw_exception_when_add_duplicated_element()
    {
        $this->collection->throwExceptionOnDuplicate();
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity1);
    }

    public function test_should_ignore_offset_and_use_identity()
    {
        $uid = $this->identity1->uId();

        $this->assertFalse($this->collection->offsetExists($uid));
        $this->assertFalse($this->collection->offsetExists($uid->id()));
        $this->collection[123] = $this->identity1;
        $this->assertTrue($this->collection->offsetExists($uid));
        $this->assertTrue($this->collection->offsetExists($uid->id()));
        $this->assertFalse($this->collection->offsetExists(123));

        $this->assertSame($this->identity1, $this->collection[1]);
        $this->assertSame($this->identity1, $this->collection[$uid]);
        $this->assertNull($this->collection[123]);
    }

    public function test_should_clear_elements()
    {
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);
        $this->assertCount(3, $this->collection);
        $this->assertFalse($this->collection->isEmpty());
        $this->collection->clear();
        $this->assertCount(0, $this->collection);
        $this->assertTrue($this->collection->isEmpty());
    }

    /**
     * @depends test_should_add_elements
     */
    public function test_should_remove_element()
    {
        $uid2 = $this->identity2->uId();
        $uid3 = $this->identity3->uId();

        $this->assertFalse($this->collection->removeElement($this->identity1));
        $this->assertNull($this->collection->remove($uid2->id()));
        $this->assertNull($this->collection->remove($uid3));

        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);
        $this->assertCount(3, $this->collection);

        $this->assertTrue($this->collection->removeElement($this->identity1));
        $this->assertSame($this->identity2, $this->collection->remove($uid2->id()));
        $this->assertSame($this->identity3, $this->collection->remove($uid3));
        $this->assertTrue($this->collection->isEmpty());
    }

    /**
     * @depends test_should_add_elements
     */
    public function test_should_contains_key()
    {
        $uid = $this->identity1->uId();

        $this->assertFalse($this->collection->containsKey($uid->id()));
        $this->assertFalse($this->collection->containsKey($uid));
        $this->collection->add($this->identity1);
        $this->assertTrue($this->collection->containsKey($uid->id()));
        $this->assertTrue($this->collection->containsKey($uid));
    }

    public function test_should_return_the_element()
    {
        $uid = $this->identity1->uId();

        $this->assertNull($this->collection->get($uid->id()));
        $this->collection->add($this->identity1);
        $this->assertSame($this->identity1, $this->collection->get($uid));
    }

    public function test_should_return_the_keys()
    {
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);
        $this->assertEquals(
            array(
                $this->identity1->uId()->id(),
                $this->identity2->uId()->id(),
                $this->identity3->uId()->id(),
            ),
            $this->collection->getKeys()
        );
    }

    public function test_should_return_the_values()
    {
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);
        $this->assertSame(
            array(
                $this->identity1,
                $this->identity2,
                $this->identity3,
            ),
            $this->collection->getValues()
        );
    }

    public function test_should_set_element()
    {
        $this->collection->set(123, $this->identity1);
        $this->assertCount(1, $this->collection);
        $this->assertTrue($this->collection->offsetExists($this->identity1->uId()));
    }

    public function test_should_unset()
    {
        $uid1 = $this->identity1->uId();
        $uid2 = $this->identity2->uId();

        unset($this->collection[$uid1->id()]);
        unset($this->collection[$uid2]);
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->assertCount(2, $this->collection);
        unset($this->collection[$uid1->id()]);
        unset($this->collection[$uid2]);
        $this->assertCount(0, $this->collection);
    }

    public function test_should_be_iterator()
    {
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);

        $i = 0 ;
        foreach ($this->collection as $key => $element)
        {
            $i++;
            $this->assertSame($i, $key);
        }
    }

    public function test_should_be_converted_to_array()
    {
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);

        $this->assertSame(
            array(
                1 => $this->identity1,
                2 => $this->identity2,
                3 => $this->identity3,
            ),
            $this->collection->toArray()
        );
    }

    public function test_should_return_first_element()
    {
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);
        $this->assertSame($this->identity1, $this->collection->first());
    }

    public function test_should_return_last_element()
    {
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);
        $this->assertSame($this->identity3, $this->collection->last());
    }

    public function test_should_change_pointer()
    {
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);

        $this->assertSame(1, $this->collection->key());
        $this->assertSame($this->identity1, $this->collection->current());

        $this->assertSame($this->identity2, $this->collection->next());
        $this->assertSame(2, $this->collection->key());
        $this->assertSame($this->identity2, $this->collection->current());

        $this->assertSame($this->identity3, $this->collection->next());
        $this->assertSame(3, $this->collection->key());
        $this->assertSame($this->identity3, $this->collection->current());
        $this->assertFalse($this->collection->next());
    }

    public function test_element_should_exists()
    {
        $closure = function ($key, UniqueIdentity $value) {
            return $value->uId()->id() == 1;
        };

        $this->assertFalse($this->collection->exists($closure));
        $this->collection->add($this->identity1);
        $this->assertTrue($this->collection->exists($closure));
    }

    public function test_should_filter_elements()
    {
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);
        $this->assertCount(1, $this->collection->filter(function(UniqueIdentity $id) { return $id->uId()->id() == 1; }));
    }

    public function test_should_map_return_the_map_of_elements_returned_by_closure()
    {
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);

        $expected = array($this->identity2);
        $closure = function (UniqueIdentity $element) {
            if ($element->uid()->id() == 2) return $element;
        };
        $newCollection = $this->collection->map($closure);
        $this->assertInstanceOf(IdentityCollection::CLASS_NAME, $newCollection);
        $this->assertSame($expected, $newCollection->toArray());
    }

    public function test_should_partition_the_collection_in_two()
    {
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);

        $closure = function ($key, UniqueIdentity $element) {
            if ($element->uId()->id() == 2) return $element;
        };
        $actual = $this->collection->partition($closure);
        $this->assertCount(2, $actual);
        $this->assertContainsOnlyInstancesOf(IdentityCollection::CLASS_NAME, $actual);
        $this->assertSame(array($this->identity2), $actual[0]->toArray());
        $this->assertSame(array($this->identity1, $this->identity3), $actual[1]->toArray());
    }

    public function testShouldReturnWhetherTheClosureApplyToAllElements()
    {
        $this->collection->add($this->identity1);

        $this->assertTrue($this->collection->forAll(function () { return true; }));
        $this->assertFalse($this->collection->forAll(function () {}));
    }

    public function testShouldSliceTheCollection()
    {
        $this->collection->add($this->identity1);
        $this->collection->add($this->identity2);
        $this->collection->add($this->identity3);

        $expected = array(
            2 => $this->identity2,
            3 => $this->identity3,
        );

        $actual = $this->collection->slice(1);

        $this->assertCount(2, $actual);
        $this->assertSame($expected, $actual);
        $this->assertCount(3, $this->collection);
    }

    public function test_should_find_index()
    {
        $this->assertFalse($this->collection->indexOf($this->identity1));
        $this->collection->add($this->identity1);
        $this->assertEquals($this->identity1->uId()->id(), $this->collection->indexOf($this->identity1));
    }
}
