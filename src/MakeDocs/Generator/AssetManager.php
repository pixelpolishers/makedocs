<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Generator;

class AssetManager
{
    /**
     * The assets that are used in the documentation.
     *
     * @var string[]
     */
    private $assets;

    /**
     * Initializes a new instance of this class.
     */
    public function __construct()
    {
        $this->assets = array();
    }

    /**
     * Adds an asset.
     *
     * @param string $name The name of the asset.
     * @param string $path The path to the asset.
     * @throws \RuntimeException
     */
    public function addAsset($name, $path)
    {
        if (!is_file($path)) {
            throw new \RuntimeException('The file "' . $path . '" does not exist.');
        }
        $this->assets[$name] = $path;
    }

    /**
     * Copies all assets to the given path.
     *
     * @param string $path The path to copy to.
     */
    public function copyTo($path)
    {
        foreach ($this->assets as $assetName => $assetPath) {
            $outputPath = $path . '/' . $assetName;

            $this->copyFromTo($assetPath, $outputPath);
        }
    }

    /**
     * Copies a single asset from a path to a path.
     *
     * @param string $from The location to copy from.
     * @param string $to The location to copy to.
     */
    private function copyFromTo($from, $to)
    {
        $directory = dirname($to);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        copy($from, $to);
    }
}
