<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Builder\Html\Element;

use DOMElement;
use MakeDocs\Builder\Html\DOMRenderer;

class AdmonitionElement extends AbstractElement
{
    private $type;

    public function __construct($type = null)
    {
        $this->type = $type;
    }

    public function render(DOMRenderer $iterator, DOMElement $element)
    {
        $type = $this->type;
        if (!$type) {
            $type = $element->getAttribute('type');
        }

        $result = '<div class="admonition ' . $type . '">';

        $result .= $iterator->renderNodeList($element);

        return $result . '</div>' . PHP_EOL;
    }
}
