<?php
/**
 * This file is part of the collection.local project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Star\Component;

/**
 * Class StarCollectionTestCase
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Star\Component
 */
class StarCollectionTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $object
     */
    protected function assertInstanceOfCollection($object)
    {
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $object);
    }
}
 