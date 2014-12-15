<?php
/**
 * This file is part of the StarCollection project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Collection\UniqueId;

/**
 * Class ConfigurableId
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Collection\UniqueId
 */
final class ConfigurableId implements UniqueId
{
    /**
     * @var mixed
     */
    private $id;

    /**
     * @param mixed $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Return the string representation of the UniqueId.
     *
     * @return string
     */
    public function id()
    {
        return strval($this->id);
    }
}
