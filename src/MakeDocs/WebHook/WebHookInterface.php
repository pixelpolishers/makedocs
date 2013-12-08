<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\WebHook;

/**
 * The interface that should be implemented by all web hooks.
 */
interface WebHookInterface
{
    /**
     * Listens for incoming requests.
     */
    public function listen();
}
