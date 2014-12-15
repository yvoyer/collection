<?php
/**
 * This file is part of the StarCollection project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Collection;

use Star\Component\Collection\UniqueId\UniqueId;

/**
 * Class UniqueIdentity
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Collection
 */
interface UniqueIdentity
{
    const INTERFACE_NAME = __CLASS__;

    /**
     * @return UniqueId
     */
    public function uId();
}
