<?php
/**
 * This file is part of the StarCollection project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Collection\UniqueId;

/**
 * Class UniqueId
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Collection\UniqueId
 */
interface UniqueId
{
    const INTERFACE_NAME = __CLASS__;

    /**
     * Return the string representation of the UniqueId.
     *
     * @return string
     */
    public function id();
}
