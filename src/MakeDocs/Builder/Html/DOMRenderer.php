<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Builder\Html;

use DOMElement;
use DOMNode;
use MakeDocs\Generator\Generator;

class DOMRenderer
{
    /**
     * The generator instance.
     *
     * @var Generator
     */
    private $generator;

    /**
     * The HTML renderer.
     *
     * @var HtmlBuilder
     */
    private $renderer;

    /**
     * Initializes a new instance of this class.
     *
     * @param HtmlBuilder $renderer The html renderer.
     */
    public function __construct(Generator $generator, HtmlBuilder $renderer)
    {
        $this->generator = $generator;
        $this->renderer = $renderer;
    }

    public function getGenerator()
    {
        return $this->generator;
    }

    public function getRenderer()
    {
        return $this->renderer;
    }

    public function renderContentElement(DOMElement $node)
    {
        $result = '';

        foreach ($node->childNodes as $childNode) {
            if ($childNode->nodeType == 1) {
                $result .= $this->renderElement($childNode);
            }
        }

        return $result;
    }

    public function renderNodes(DOMElement $element)
    {
        $result = '';
        foreach ($element->childNodes as $node) {
            $result .= $this->renderNode($node);
        }
        return $result;
    }

    public function renderNode(DOMNode $node)
    {
        $result = '';
        if ($node->nodeType == 3) {
            $result .= $node->nodeValue;
        } else if ($node->nodeType == 1) {
            $result .= $this->renderElement($node);
        } else if ($node->nodeType == 7) {
            $result .= '<?' . $node->target . ' ' . $node->data . ' ?>';
        }
        return $result;
    }

    public function renderElement(DOMElement $element)
    {
        $nodeName = $element->nodeName;

        if (!$this->renderer->getElementRegister()->has($nodeName)) {
            throw new \RuntimeException('Cannot render unknown node ' . $nodeName);
        }

        $renderer = $this->renderer->getElementRegister()->get($nodeName);

        return $renderer->render($this, $element);
    }
}
