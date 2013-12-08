<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Builder\Html;

use MakeDocs\Generator\Generator;
use MakeDocs\Builder\Html\HtmlBuilder;
use MakeDocs\Builder\Html\Environment\EnvironmentInterface;

class Template
{
    private $generator;
    private $renderer;
    private $environments;

    public function __construct(Generator $generator, HtmlBuilder $renderer)
    {
        $this->generator = $generator;
        $this->renderer = $renderer;
        $this->environments = array();

        $this->loadAssets($this->renderer->getThemeDirectory());
    }

    public function addEnvironment($name, EnvironmentInterface $environment)
    {
        $this->environments[$name] = $environment;
    }

    private function loadAssets($themeDirectory)
    {
        if (!is_dir($themeDirectory)) {
            throw new \RuntimeException('Invalid theme directory provided.');
        }

        $configPath = $themeDirectory . '/makedocs.json';
        $configJson = json_decode(file_get_contents($configPath));

        if (isset($configJson->assets)) {
            $assetManager = $this->generator->getAssetManager();
            foreach ($configJson->assets as $name) {
                $path = $themeDirectory . '/' . $name;
                if (is_file($path)) {
                    $assetManager->addAsset($name, $path);
                } else {
                    foreach (glob($path) as $path) {
                        $name = substr($path, strlen($themeDirectory) + 1);

                        $assetManager->addAsset($name, $path);
                    }
                }
            }
        }
    }

    public function parse($page, $content)
    {
        $loader = new \Twig_Loader_Filesystem($this->renderer->getThemeDirectory());

        $twig = new \Twig_Environment($loader);

        return $twig->render('page.html', $this->environments);
    }
}
