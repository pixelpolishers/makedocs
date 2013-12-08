<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Builder\Html\Environment;

class ProjectEnvironment implements EnvironmentInterface
{
    private $title;
    private $url;
    private $toc;
    private $github;
    private $generated;
    private $googleAnalytics;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getToc()
    {
        return $this->toc;
    }

    public function setToc($toc)
    {
        $this->toc = $toc;
    }

    public function getGithub()
    {
        return $this->github;
    }

    public function setGithub($github)
    {
        $this->github = $github;
    }

    public function getGenerated()
    {
        return $this->generated;
    }

    public function setGenerated($generated)
    {
        $this->generated = $generated;
    }

    public function getGoogleAnalytics()
    {
        return $this->googleAnalytics;
    }

    public function setGoogleAnalytics($googleAnalytics)
    {
        $this->googleAnalytics = $googleAnalytics;
    }
}
