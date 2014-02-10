<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\WebHook\GitHub;

class Payload
{
    private $repository;
    private $ref;

    public function __construct($payload)
    {
        $this->parse($payload);
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getRef()
    {
        return $this->ref;
    }

    public function getBranch()
    {
        $ref = $this->getRef();
        $parts = explode('/', $ref);
        return array_pop($parts);
    }

    private function parse($payload)
    {
        $object = json_decode($payload);

        $this->ref = $object->ref;
        $this->repository = $object->repository->name;
    }
}
