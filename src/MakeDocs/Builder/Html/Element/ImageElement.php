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

class ImageElement extends AbstractElement
{
    public function render(DOMRenderer $renderer, DOMElement $element)
    {
        $generator = $renderer->getGenerator();

        $src = $element->getAttribute('src');

        $inputDir = $generator->getInputDirectory();
        $path = $renderer->getRenderer()->getBaseUrl() . '/' . $src;

        $assetManager = $generator->getAssetManager();
        $assetManager->addAsset($src, $inputDir . '/' . $src);

        return '<img src="' . $path . '" alt="image" width=300 />';
    }
}
