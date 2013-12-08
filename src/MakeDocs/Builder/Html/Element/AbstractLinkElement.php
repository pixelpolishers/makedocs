<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Builder\Html\Element;

use MakeDocs\Builder\Html\DOMRenderer;

abstract class AbstractLinkElement extends AbstractElement
{
    protected function parseLink(DOMRenderer $renderer, $href)
    {
        $result = array();

        $urlInfo = parse_url($href);
        if (!array_key_exists('scheme', $urlInfo)) {
            $renderer->getGenerator()->getPageManager()->getQueue()->enqueue($href);
            $result['href'] = $renderer->getRenderer()->getLink($href);
            $result['path'] = $href;
        } else {
            $result['href'] = $href;
            $result['path'] = null;
        }

        return $result;
    }
}
