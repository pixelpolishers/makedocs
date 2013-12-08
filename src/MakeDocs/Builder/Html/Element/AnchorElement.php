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

class AnchorElement extends AbstractLinkElement
{
    public function render(DOMRenderer $renderer, DOMElement $element)
    {
        $href = $element->getAttribute('href');

        $link = $this->parseLink($renderer, $href);

        return '<a href="' . $link['href'] . '">' . $renderer->renderNodes($element) . '</a>';
    }
}
