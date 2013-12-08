<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Builder\Html;

use MakeDocs\Generator\Generator;
use MakeDocs\Generator\SourceFile;
use MakeDocs\Builder\AbstractBuilder;
use MakeDocs\Builder\Html\Element\AdmonitionElement;
use MakeDocs\Builder\Html\Element\AnchorElement;
use MakeDocs\Builder\Html\Element\BreakElement;
use MakeDocs\Builder\Html\Element\CodeElement;
use MakeDocs\Builder\Html\Element\EmphasisElement;
use MakeDocs\Builder\Html\Element\HeaderElement;
use MakeDocs\Builder\Html\Element\ImageElement;
use MakeDocs\Builder\Html\Element\IncludeElement;
use MakeDocs\Builder\Html\Element\ListElement;
use MakeDocs\Builder\Html\Element\ParagraphElement;
use MakeDocs\Builder\Html\Element\StrongElement;
use MakeDocs\Builder\Html\Element\TableElement;
use MakeDocs\Builder\Html\Element\TableColumnElement;
use MakeDocs\Builder\Html\Element\TableHeaderElement;
use MakeDocs\Builder\Html\Element\TableRowElement;

class HtmlBuilder extends AbstractBuilder
{
    /**
     * The register with all element renderers.
     *
     * @var ElementRegister
     */
    private $elementRegister;

    /**
     * The base url of the documentation.
     *
     * @var string
     */
    private $baseUrl;

    /**
     * The path to the output directory.
     *
     * @var string
     */
    private $outputDirectory;

    /**
     * The path to the theme directory.
     *
     * @var string
     */
    private $themeDirectory;

    /**
     * Gets the element register.
     *
     * @return ElementRegister
     */
    public function getElementRegister()
    {
        if ($this->elementRegister === null) {
            $this->elementRegister = new ElementRegister();
            $this->initializeElementRegister($this->elementRegister);
        }
        return $this->elementRegister;
    }

    /**
     * Initializes athe element register.
     *
     * @param ElementRegister $register The register to initialize.
     */
    protected function initializeElementRegister(ElementRegister $register)
    {
        $register->set('a', new AnchorElement());
        $register->set('admonition', new AdmonitionElement());
        $register->set('br', new BreakElement());
        $register->set('code', new CodeElement());
        $register->set('em', new EmphasisElement());
        $register->set('error', new AdmonitionElement('error'));
        $register->set('header', new HeaderElement());
        $register->set('h1', new HeaderElement(1));
        $register->set('h2', new HeaderElement(2));
        $register->set('h3', new HeaderElement(3));
        $register->set('h4', new HeaderElement(4));
        $register->set('h5', new HeaderElement(5));
        $register->set('h6', new HeaderElement(6));
        $register->set('hint', new AdmonitionElement('hint'));
        $register->set('img', new ImageElement());
        $register->set('include', new IncludeElement());
        $register->set('list', new ListElement());
        $register->set('note', new AdmonitionElement('note'));
        $register->set('p', new ParagraphElement());
        $register->set('spoiler', new AdmonitionElement('spoiler'));
        $register->set('strong', new StrongElement());
        $register->set('table', new TableElement());
        $register->set('td', new TableColumnElement());
        $register->set('th', new TableHeaderElement());
        $register->set('tr', new TableRowElement());
        $register->set('tip', new AdmonitionElement('tip'));
        $register->set('warning', new AdmonitionElement('warning'));
    }

    /**
     * Gets the base url.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Sets the base url.
     *
     * @param string $baseUrl The base url to set.
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Gets the output directory.
     *
     * @return string
     */
    public function getOutputDirectory()
    {
        return $this->outputDirectory;
    }

    /**
     * Sets the output directory.
     *
     * @param string $outputDirectory The output directory.
     */
    public function setOutputDirectory($outputDirectory)
    {
        $this->outputDirectory = $outputDirectory;
    }

    /**
     * Gets the theme directory.
     *
     * @return string
     */
    public function getThemeDirectory()
    {
        return $this->themeDirectory;
    }

    /**
     * Sets the theme directory.
     *
     * @param string $themeDirectory The theme directory.
     */
    public function setThemeDirectory($themeDirectory)
    {
        $this->themeDirectory = $themeDirectory;
    }

    /**
     * Creates an id.
     *
     * @param string $name The name of the id.
     * @return string
     */
    public function createId($name)
    {
        return strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
    }

    /**
     * Gets the link for the given source file.
     *
     * @param SourceFile $page The page to get the link for.
     * @param string $header An optional header to link to.
     * @return string
     */
    public function getLink($page, $header = null)
    {
        $id = preg_replace('/[^a-z0-9\/]+/i', '', $page);
        $link = $this->getBaseUrl() . '/' . $id;

        if ($header !== null) {
            $link .= '#' . $this->createId($header);
        } else if ($id == 'index') {
            $link = $this->getBaseUrl();
        }

        return $link;
    }

    /**
     * Builds the source files.
     *
     * @param Generator $generator The generator to build for.
     */
    public function build(Generator $generator)
    {
        $renderer = new DOMRenderer($generator, $this);
        $publisher = new Publisher($generator, $this);

        $pageManager = $this->loadPageManager($generator);
        while (!$pageManager->getQueue()->isEmpty()) {
            $page = $pageManager->getQueue()->dequeue();

            if ($pageManager->hasPage($page)) {
                continue;
            }

            // Parse the source file:
            $sourceFile = $pageManager->getSourceFile($page);

            // Register the page:
            $pageManager->addPage($page, $sourceFile);

            // Parse the content:
            $content = $renderer->renderContentElement($sourceFile->getContent());

            // And add the content to the publisher:
            $publisher->add($page, $content);
        }

        $publisher->publish();
    }

    private function loadPageManager(Generator $generator)
    {
        $config = $generator->getConfig();

        // Initialize the page manager:
        $manager = $generator->getPageManager();
        $manager->getQueue()->enqueue($config->getMaster());

        // Make sure that all TOC entries are part of the builder:
        foreach ($config->getToc() as $entry) {
            $manager->getQueue()->enqueue($entry);
        }

        return $manager;
    }
}
