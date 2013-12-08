<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\WebHook\GitHub;

use MakeDocs\Generator\Config;

class Payload
{
    private $ref;

    public function __construct($payload)
    {
        $this->parse($payload);
    }

    private function parse($payload)
    {
        $object = json_decode($payload);

        $this->ref = $object->ref;
    }

    public function updateConfig(Config $config)
    {
        $config->setVersion('1.0.0');
    }
}
