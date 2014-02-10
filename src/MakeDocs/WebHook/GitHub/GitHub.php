<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\WebHook\GitHub;

use MakeDocs\Driver\DriverConfig;
use MakeDocs\Driver\GitDriver;
use MakeDocs\Generator\Generator;
use MakeDocs\WebHook\AbstractWebHook;
use MakeDocs\WebHook\InvalidRequestException;

/**
 * The GitHub webhook listens for incoming requests from GitHub.
 */
class GitHub extends AbstractWebHook
{
    private $generator;
    private $parameter;
    private $remoteAddress;
    private $configurations;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
        $this->parameter = 'payload';
        $this->configurations = array();
    }

    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
    }

    public function getGenerator()
    {
        return $this->generator;
    }

    public function getRemoteAddress()
    {
        if (!$this->remoteAddress) {
            $this->setRemoteAddress($_SERVER['REMOTE_ADDR']);
        }
        return $this->remoteAddress;
    }

    public function setRemoteAddress($remoteAddress)
    {
        $this->remoteAddress = $remoteAddress;
    }

    protected function isValidRemoteAddress($remoteAddress)
    {
        if ($remoteAddress === '127.0.0.1') {
            return true;
        }

        $min = ip2long('192.30.252.0');
        $max = ip2long('192.30.252.255');
        $needle = ip2long($remoteAddress);

        return ($needle >= $min) && ($needle <= $max);
    }

    public function addConfiguration(Config $configuration)
    {
        $this->configurations[] = $configuration;
    }

    public function getConfiguration($name)
    {
        foreach ($this->configurations as $configuration) {
            if ($configuration->getName() == $name) {
                return $configuration;
            }
        }
        return null;
    }

    public function getConfigurations()
    {
        return $this->configurations;
    }

    public function setConfigurations($configurations)
    {
        $this->configurations = array();
        foreach ($configurations as $configuration) {
            $this->addConfiguration($configuration);
        }
    }

    private function configureHtmlBuilder(Config $configuration, Payload $payload, $builderConfig)
    {
        $builder = new \MakeDocs\Builder\Html\HtmlBuilder();

        if (!array_key_exists('baseUrl', $builderConfig)) {
            throw new \InvalidArgumentException('No baseUrl setting has been set.');
        }

        if (!array_key_exists('theme', $builderConfig) || !is_dir($builderConfig['theme'])) {
            throw new \InvalidArgumentException('The theme directory "' . $builderConfig['theme'] . '" could not be found.');
        }

        if (!array_key_exists('output', $builderConfig)) {
            throw new \InvalidArgumentException('No output directory setting has been set.');
        }

        $builder->setThemeDirectory($builderConfig['theme']);

        $baseUrl = $builderConfig['baseUrl'];
        $baseUrl = str_replace('{project}', $configuration->getName(), $baseUrl);
        $baseUrl = str_replace('{version}', $payload->getBranch(), $baseUrl);
        $builder->setBaseUrl($baseUrl);

        $outputDirectory = $builderConfig['output'];
        $outputDirectory = str_replace('{project}', $configuration->getName(), $outputDirectory);
        $outputDirectory = str_replace('{version}', $payload->getBranch(), $outputDirectory);
        $builder->setOutputDirectory($outputDirectory);

        return $builder;
    }

    private function configureGenerator(Config $configuration, Payload $payload)
    {
        $inputDir = $this->detectConfigFile($configuration->getSource());
        $this->generator->setInputDirectory($inputDir);

        foreach ($configuration->getBuilders() as $builder) {
            switch ($builder['type']) {
                case 'html':
                    $builder = $this->configureHtmlBuilder($configuration, $payload, $builder);
                    break;

                default:
                    throw new \RuntimeException('Invalid builder type: ' . $builder['type']);
            }

            $this->generator->addBuilder($builder);
        }

        return $this->generator;
    }

    /**
     * Listens for incoming requests.
     *
     * @throws InvalidRequestException
     */
    public function listen()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new InvalidRequestException('Expected a POST request.');
        }

        $remoteAddress = $this->getRemoteAddress();
        if (!$this->isValidRemoteAddress($remoteAddress)) {
            throw new InvalidRequestException('Invalid remote address.');
        }

        if (empty($_POST[$this->parameter])) {
            throw new InvalidRequestException('Expected the parameter ' . $this->parameter);
        }

        $payload = new Payload($_POST['payload']);
        $configuration = $this->getConfiguration($payload->getRepository());
        if (!$configuration) {
            throw new InvalidRequestException('Invalid repository: ' . $payload->getRepository());
        }

        if ($configuration->getDriver() != 'git') {
            throw new InvalidRequestException('Project is not a git repository but ' . $configuration->getDriver());
        }

        $driverConfig = new DriverConfig();
        $driverConfig->setDirectory($configuration->getSource());
        $driverConfig->setBranch($payload->getBranch());
        $driverConfig->setRepository($configuration->getRepository());

        $driver = new GitDriver();
        $driver->install($driverConfig);

        $generator = $this->configureGenerator($configuration, $payload);
        $generator->generate();
    }
}
