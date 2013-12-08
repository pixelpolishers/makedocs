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

class IncludeElement extends AbstractElement
{
    public function render(DOMRenderer $iterator, DOMElement $element)
    {
        $sourceFile = $iterator->getGenerator()->getPageManager()->getSourceFile($element->nodeValue);

        return $iterator->renderContentElement($sourceFile->getContent());
    }
}
