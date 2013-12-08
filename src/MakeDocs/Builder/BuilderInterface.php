<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Builder;

use MakeDocs\Generator\Generator;

/**
 * The interface that should be implemented by all builders.
 */
interface BuilderInterface
{
    /**
     * Sets the configuration of the builder.
     *
     * @param array $config The configuration to set.
     */
    public function setConfig(array $config);

    /**
     * Builds the source files.
     *
     * @param Generator $generator The generator to build for.
     */
    public function build(Generator $generator);
}
