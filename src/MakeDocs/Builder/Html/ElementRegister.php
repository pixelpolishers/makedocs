<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Builder\Html;

use MakeDocs\Builder\Html\Element\AbstractElement;

class ElementRegister
{
    private $elements;

    public function __construct()
    {
        $this->elements = array();
    }

    public function clear()
    {
        $this->elements = array();
    }

    public function get($name)
    {
        return $this->elements[$name];
    }

    public function has($name)
    {
        return array_key_exists($name, $this->elements);
    }

    public function remove($name)
    {
        unset($this->elements[$name]);
    }

    public function set($name, AbstractElement $element)
    {
        $this->elements[$name] = $element;
    }
}
