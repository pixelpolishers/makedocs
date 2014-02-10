<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\WebHook\GitHub;

class Config
{
    private $name;
    private $driver;
    private $source;
    private $repository;
    private $builders;

    public function __construct()
    {
        $this->builders = array();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function setDriver($driver)
    {
        $this->driver = $driver;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }
    
    public function addBuilder(array $config)
    {
        $this->builders[] = $config;
    }

    public function getBuilders()
    {
        return $this->builders;
    }

    public function setBuilders($builders)
    {
        $this->builders = array();
        foreach ($builders as $builder) {
            $this->addBuilder($builder);
        }
    }
}
