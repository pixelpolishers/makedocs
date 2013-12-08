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

class ListElement extends AbstractElement
{
    public function render(DOMRenderer $iterator, DOMElement $element)
    {
        $result = '';

        $type = $element->getAttribute('type');
        if (!$type) {
            $type = 'disc';
        }

        $result .= '<ul type="' . $type . '">' . PHP_EOL;
        foreach ($element->getElementsByTagName('li') as $node) {
            $result .= "\t" . '<li>' . $iterator->renderNodes($node) . '</li>' . PHP_EOL;
        }
        $result .= '</ul>';

        return $result . PHP_EOL . PHP_EOL;
    }
}
