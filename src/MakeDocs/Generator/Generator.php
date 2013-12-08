<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Generator;

use MakeDocs\Builder\BuilderInterface;
use SplQueue;

/**
 * The generator is able to generate documentation using different renderers.
 */
class Generator
{
    /**
     * The asset database that is used to locate and manage assets.
     *
     * @var AssetManager
     */
    private $assetManager;

    /**
     * The page manager that holds all pages.
     *
     * @var PageManager
     */
    private $pageManager;

    /**
     * The path to the directory of where the source files are located.
     *
     * @var string
     */
    private $inputDirectory;

    /**
     * A list with all builders for this generator.
     *
     * @var BuilderInterface[]
     */
    private $builders;

    /**
     * The configuration that is loaded.
     *
     * @var Config
     */
    private $config;

    /**
     * Initializes a new instance of this class.
     */
    public function __construct()
    {
        $this->builders = array();
    }

    /**
     * Gets the asset manager.
     *
     * @return AssetManager
     */
    public function getAssetManager()
    {
        if ($this->assetManager === null) {
            $this->assetManager = new AssetManager();
        }
        return $this->assetManager;
    }

    /**
     * Gets the page manager.
     *
     * @return PageManager
     */
    public function getPageManager()
    {
        if ($this->pageManager === null) {
            $this->pageManager = new PageManager($this);
        }
        return $this->pageManager;
    }

    /**
     * Gets the page queue.
     *
     * @return SplQueue
     */
    public function getPageQueue()
    {
        if ($this->pageQueue === null) {
            $this->pageQueue = new SplQueue();
        }
        return $this->pageQueue;
    }

    /**
     * Gets the path to the input directory.
     *
     * @return string
     */
    public function getInputDirectory()
    {
        return $this->inputDirectory;
    }

    /**
     * Sets the path to the input directory.
     *
     * @param string $inputDirectory The input directory to set.
     */
    public function setInputDirectory($inputDirectory)
    {
        $this->inputDirectory = $inputDirectory;
    }

    /**
     * Adds a builder to the generator.
     *
     * @param BuilderInterface $builder The builder to add.
     */
    public function addBuilder(BuilderInterface $builder)
    {
        $this->builders[] = $builder;
    }

    /**
     * Gets the builders.
     *
     * @return BuilderInterface[]
     */
    public function getBuilders()
    {
        return $this->builders;
    }

    /**
     * Sets the iterator with all the builders.
     *
     * @param array|Traversable $builders The list with builders.
     */
    public function setBuilders($builders)
    {
        foreach ($builders as $renderer) {
            $this->addBuilder($renderer);
        }
    }

    /**
     * Gets the configuration of the project to generate the documentation for.
     *
     * @return Config
     * @throws \RuntimeException
     */
    public function getConfig()
    {
        if ($this->config === null) {
            $path = $this->getInputDirectory() . '/makedocs.json';
            if (!is_file($path)) {
                throw new \RuntimeException('The input directory does not contain a makedocs.json file.');
            }

            $data = json_decode(file_get_contents($path));
            if (!$data) {
                throw new \RuntimeException('The JSON could not be loaded.');
            }

            $this->config = new Config((array)$data);
        }
        return $this->config;
    }

    /**
     * Generates the documentation for all the renderers.
     */
    public function generate()
    {
        if (!is_dir($this->getInputDirectory())) {
            throw new \RuntimeException('The MakeDocs input directory does not exist.');
        }

        if (!$this->builders) {
            throw new \RuntimeException('No MakeDocs builders present.');
        }

        // First we change the working directory to the input, this makes rendering easier since
        // all paths are relative to source directory:
        $workingDirectory = getcwd();
        chdir($this->getInputDirectory());

        foreach ($this->builders as $builder) {
            $builder->build($this);
        }

        // Restore the working directory to its original form:
        chdir($workingDirectory);
    }
}
