<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Builder;

abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * Sets the configuration of the builder.
     *
     * @param array $config The configuration to set.
     */
    public function setConfig(array $config)
    {
        foreach ($config as $name => $value) {
            $setter = 'set' . ucfirst($name);

            call_user_func_array(array($this, $setter), array($value));
        }
    }
}
