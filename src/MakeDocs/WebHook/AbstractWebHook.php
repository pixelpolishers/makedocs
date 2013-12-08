<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\WebHook;

use MakeDocs\Driver\DriverConfig;
use MakeDocs\Generator\GeneratorConfig;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * The base class for all webhooks.
 */
abstract class AbstractWebHook implements WebHookInterface
{
    protected function detectInputPath(DriverConfig $driverConfig, GeneratorConfig $generatorConfig)
    {
        $it = new RecursiveDirectoryIterator($driverConfig->getDirectory());
        $objects = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $fileInfo) {
            if ($fileInfo->isFile() && $fileInfo->getFilename() == 'makedocs.xml') {
                echo $fileInfo->getPathname();
                break;
            }
        }

        echo '<pre>';
        print_r($generatorConfig);
        exit;
    }
}
