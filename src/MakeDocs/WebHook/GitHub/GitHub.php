<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\WebHook\GitHub;

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

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
        $this->parameter = 'payload';
    }

    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * Listens for incoming requests.
     *
     * @throws InvalidRequestException
     */
    public function listen()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new InvalidRequestException();
        }

        if (empty($_POST[$this->parameter])) {
            throw new InvalidRequestException();
        }

        // @todo Parse the payload to set the driver configuration:
        //$payload = new Payload($_POST['payload']);

//        $driver = new \MakeDocs\Driver\GitDriver();
//        $driver->install(array(
//            'repository' => 'https://github.com/pixelpolishers/resolver.git',
//            'branch' => 'develop',
//            'directory' => getcwd(),
//        ));

        $this->generator->generate();
    }
}
