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
use MakeDocs\Builder\Html\HtmlBuilder;
use MakeDocs\Builder\Html\Environment\BreadcrumbEnvironment;
use MakeDocs\Builder\Html\Environment\PageEnvironment;
use MakeDocs\Builder\Html\Environment\ProjectEnvironment;

class Publisher
{
    private $pages;
    private $generator;
    private $renderer;

    public function __construct(Generator $generator, HtmlBuilder $renderer)
    {
        $this->pages = array();
        $this->generator = $generator;
        $this->renderer = $renderer;
    }

    public function add($page, $content)
    {
        $this->pages[$page] = $content;
    }

    public function publish()
    {
        $config = $this->generator->getConfig();
        $template = new Template($this->generator, $this->renderer);

        $breadcrumbEnvironment = new BreadcrumbEnvironment();
        $template->addEnvironment('breadcrumb', $breadcrumbEnvironment);

        $pageEnvironment = new PageEnvironment();
        $template->addEnvironment('page', $pageEnvironment);

        $projectEnvironment = new ProjectEnvironment();
        $projectEnvironment->setTitle($config->getName());
        $projectEnvironment->setUrl($this->renderer->getBaseUrl());
        $projectEnvironment->setToc($this->buildTableOfContents());
        $projectEnvironment->setGithub($config->getGithub());
        $projectEnvironment->setGenerated(new \DateTime());
        $projectEnvironment->setGoogleAnalytics($config->getGoogleAnalytics());
        $template->addEnvironment('project', $projectEnvironment);

        foreach ($this->pages as $page => $content) {
            $sourceFile = $this->generator->getPageManager()->getPage($page);

            // Update the page environment:
            $pageEnvironment->setTitle($sourceFile->getFirstHeader(1));
            $pageEnvironment->setContent($content);
            $pageEnvironment->setUrl($this->renderer->getLink($sourceFile->getName()));

            $this->publishPage($template, $page, $content);
        }

        $assets = $this->generator->getAssetManager();
        $assets->copyTo($this->renderer->getOutputDirectory());

        $this->publishHtAccess();
    }

    private function publishHtAccess()
    {
        $src = 'Options +FollowSymLinks
RewriteEngine On

# Redirect to HTML if it exists.
# e.g. example.com/foo will display the contents of example.com/foo.html
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME}.html -f
RewriteRule ^(.+)$ $1.html [L,QSA]';

        $path = $this->renderer->getOutputDirectory() . '/.htaccess';

        file_put_contents($path, $src);
    }

    private function publishPage(Template $template, $page, $content)
    {
        $outputFile = $this->getOutputFile($page);

        $compiled = $template->parse($page, $content);

        file_put_contents($outputFile, $compiled);
    }

    private function getOutputFile($page)
    {
        $outputFile = $this->renderer->getOutputDirectory() . '/' . $page . '.html';

        $outputDir = dirname($outputFile);
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        return $outputFile;
    }

    private function buildTableOfContents()
    {
        $result = array();

        $toc = $this->generator->getConfig()->getToc();
        foreach ($toc as $item) {
            $page = $this->generator->getPageManager()->getPage($item);

            $result[] = $this->getTocEntry($item, $page);
        }

        return $result;
    }

    private function getTocEntry($item, SourceFile $page)
    {
        $result = array(
            'page' => $item,
            'title' => $page->getFirstHeader(1),
            'url' => $this->renderer->getLink($page->getName()),
            'childs' => $this->getTocEntries($page, 2),
        );

        return $result;
    }

    private function getTocEntries(SourceFile $page, $level)
    {
        $result = array();

        foreach ($page->getHeaders($level) as $header) {
            $result[] = array(
                'title' => $header,
                'url' => $this->renderer->getLink($page->getName(), $header),
                'childs' => $this->getTocEntries($page, $level + 1),
            );
        }

        return $result;
    }
}
