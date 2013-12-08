<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Generator;

use SplQueue;

class PageManager
{
    /**
     * The generator.
     *
     * @var Generator
     */
    private $generator;

    /**
     * A list with all parsed source files.
     *
     * @var SourceFile[]
     */
    private $pages;

    /**
     * The queue with pages that should be handled.
     *
     * @var SplQueue
     */
    private $queue;

    /**
     * Initializes a new instance of this class.
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
        $this->pages = array();
        $this->queue = new SplQueue();
    }

    public function addPage($id, SourceFile $source)
    {
        $this->pages[$id] = $source;
    }

    public function getPage($id)
    {
        return $this->pages[$id];
    }

    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Loads a source file.
     *
     * @param string $page The page to load.
     * @return SourceFile
     */
    public function getSourceFile($page)
    {
        $path = realpath($this->generator->getInputDirectory() . '/' . $page . '.xml');

        return new SourceFile($page, $path);
    }

    public function hasPage($id)
    {
        return array_key_exists($id, $this->pages);
    }
}
