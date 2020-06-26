<?php
declare(strict_types  = 1);

namespace Dobreff\Components;

/**
 * Abstract class, holds the setParent functionality for members of the composite class
 *
 */
abstract class Component
{

    /**
     * Holds pointer to the composite class
     *
     * @access private
     * @var object $parent
     */
    private $parent = null;

 
    /**
     * Sets the parent class
     *
     * @throws \InvalidArgumentException
     *
     * @param object $parent
     */
   public function setParent(&$parent) : void
    {
        if (is_object($parent)) {
            $this->parent = $parent;

            return;
        }

        throw new \InvalidArgumentException('Given param is not an Object');
    }

    public function getParent()
    {
        return $this->parent;
    }
}
