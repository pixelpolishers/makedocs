<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\Generator;

class Config
{
    private $name;
    private $properties;
    private $master;
    private $toc;
    private $github;
    private $googleAnalytics;

    public function __construct(array $data = array())
    {
        $this->properties = array();
        $this->master = 'index';

        foreach ($data as $name => $value) {
            $setter = 'set' . ucfirst($name);

            if (!method_exists($this, $setter)) {
                throw new \RuntimeException('The setting ' . $name . ' does not exist.');
            }

            call_user_func(array($this, $setter), $value);
        }
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function setDirectory($path)
    {
        $this->directory = $path;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function setProperties($properties)
    {
        $this->properties = (array)$properties;
    }

    public function getMaster()
    {
        return $this->master;
    }

    public function setMaster($master)
    {
        $this->master = $master;
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

    public function getGoogleAnalytics()
    {
        return $this->googleAnalytics;
    }

    public function setGoogleAnalytics($googleAnalytics)
    {
        $this->googleAnalytics = $googleAnalytics;
    }
}
