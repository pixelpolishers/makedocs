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

class HeaderElement extends AbstractElement
{
    private $level;

    public function __construct($level = null)
    {
        $this->level = (int)$level;
    }

    public function render(DOMRenderer $domRenderer, DOMElement $element)
    {
        $level = $this->level;
        if (!$level) {
            $level = $element->getAttribute('level');
        }
        $level = max(1, $level);

        $value = $domRenderer->renderNodes($element);
        $result = '';

        $id = $domRenderer->getRenderer()->createId($value);

        if ($level > 6) {
            $result = '<div id="' . $id . '" class="header' . $level . '">' . $value . '</div>';
        } else {
            $result = '<h' . $level . ' id="' . $id . '">' . $value . '</h' . $level . '>';
        }

        return $result . PHP_EOL . PHP_EOL;
    }

}
