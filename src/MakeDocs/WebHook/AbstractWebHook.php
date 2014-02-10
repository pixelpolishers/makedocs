<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\WebHook;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * The base class for all webhooks.
 */
abstract class AbstractWebHook implements WebHookInterface
{
    protected function detectConfigFile($path)
    {
        $it = new RecursiveDirectoryIterator($path);
        $objects = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $fileInfo) {
            if ($fileInfo->isFile() && $fileInfo->getFilename() == 'makedocs.json') {
                return dirname(realpath($fileInfo->getPathname()));
            }
        }

        return null;
    }
}
