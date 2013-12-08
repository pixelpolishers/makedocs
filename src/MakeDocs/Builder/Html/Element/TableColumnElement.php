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

class TableColumnElement extends AbstractElement
{
    public function render(DOMRenderer $iterator, DOMElement $element)
    {
        $colspan = max(1, $element->getAttribute('colspan'));
        $rowspan = max(1, $element->getAttribute('rowspan'));

        $result = '';

        $result .= "\t\t";
        $result .= sprintf('<td colspan="%d" rowspan="%d">', $colspan, $rowspan);
        $result .= $iterator->renderNodes($element);
        $result .= '</td>' . PHP_EOL;

        return $result;
    }

}
