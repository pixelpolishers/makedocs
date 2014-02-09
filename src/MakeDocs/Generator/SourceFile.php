<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Generator;

use DOMDocument;
use DOMElement;

class SourceFile
{
    /**
     * The name of the file.
     *
     * @var string
     */
    private $name;

    /**
     * The DOM document.
     *
     * @var DOMDocument
     */
    private $dom;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $name The name of the page.
     * @param string $path The path to the file.
     */
    public function __construct($name, $path)
    {
        if (!is_file($path)) {
            throw new \RuntimeException('The source file "' . $path . '" (' . $name . ') does not exist.');
        }

        $this->name = $name;

        $this->dom = new DOMDocument();
        $this->dom->load($path);
    }

    /**
     * Gets the name of page.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the content section.
     *
     * @return DOMElement
     */
    public function getContent()
    {
        foreach ($this->dom->documentElement->childNodes as $node) {
            if ($node->nodeName == 'content') {
                return $node;
            }
        }

        return null;
    }

    public function getFirstHeader($level)
    {
        $tag = 'h' . max(1, $level);

        $elements = $this->dom->documentElement->getElementsByTagName($tag);
        $element = $elements->item(0);

        return $this->getText($element);
    }

    public function getHeaders($level)
    {
        $tag = 'h' . max(1, $level);
        $elements = $this->dom->documentElement->getElementsByTagName($tag);

        $result = array();
        foreach ($elements as $element) {
            $result[] = $this->getText($element);
        }
        return $result;
    }

    public function getTemplate()
    {
        $element = $this->dom->documentElement;

        $result = 'page';
        if ($element->hasAttribute('html-template')) {
            $result = $element->getAttribute('html-template');
        }
        return $result;
    }

    private function getText(DOMElement $element)
    {
        $result = '';

        foreach ($element->childNodes as $node) {
            if ($node->nodeType == 1) {
                $result .= $this->getText($node);
            } else if ($node->nodeType == 3) {
                $result .= $node->nodeValue;
            }
        }

        return $result;
    }
}
