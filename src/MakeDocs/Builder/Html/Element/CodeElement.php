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

class CodeElement extends AbstractElement
{
    public function render(DOMRenderer $iterator, DOMElement $element)
    {
        $code = '';
        $language = $element->getAttribute('lang');
        $newLine = $language == 'php';

        foreach ($element->childNodes as $child) {
            if ($child->nodeType == 3) {
                $code .= $child->nodeValue;
            } else if ($child->nodeType == 7 && $newLine) {
                $code .= '<?php' . PHP_EOL;
                $code .= trim($child->data) . PHP_EOL;
                $code .= '?>';
            } else if ($child->nodeType == 7) {
                $code .= '<?' . $child->target . ' ';
                $code .= $child->data;
                $code .= '?>';
            }
        }

        return '<code class="' . $language . '">' . htmlspecialchars(trim($code)) . '</code>';
    }
}
